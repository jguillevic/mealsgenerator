<?php

namespace DAL\ShoppingList;

use Framework\DAL\Database;
use Framework\DAL\DALHelper;
use Framework\Tools\Error\ErrorManager;
use Model\ShoppingList\ShoppingListItem;

class ShoppingListItemDAL
{
    private $db;
    
	public function __construct(Database $db = null)
	{
		if (isset($db))
			$this->db = $db;
		else
			$this->db = new Database();
    }

    public function Load(array $shoppingListIds) : array
    {
        try
        {
            $query = "SELECT SLI.Id
                    , SLI.Content
                    , SLI.IsHandled
                    , SLI.ShoppingListId
                    FROM ShoppingListItem AS SLI
                    WHERE ";

            $params = [];
            $query .= DALHelper::SetArrayParams($shoppingListIds, "SLI", "ShoppingListId", $params);

            $query .= " ORDER BY SLI.Id;";

            $this->db->BeginTransaction();

            $rows = $this->db->Read($query, $params);

            $this->db->Commit();

            $shoppingListItems = [];

            foreach ($rows as $row)
            {
                $shoppingListItem = new ShoppingListItem();
                
                $shoppingListItem->SetId($row["Id"]);
                $shoppingListItem->SetContent($row["Content"]);
                $shoppingListItem->SetIsHandled($row["IsHandled"]);

                $shoppingListId = $row["ShoppingListId"];

                if (!array_key_exists($shoppingListId, $shoppingListItems))
                    $shoppingListItems[$shoppingListId] = [];

                $shoppingListItems[$shoppingListId][$shoppingListItem->GetId()] = $shoppingListItem;
            }

            return $shoppingListItems;
        }
        catch (\Exception $e)
        {
            $this->db->Rollback();

            ErrorManager::Manage($e);
        }
    } 

    public function Add(array $shoppingListItems) : void
    {
        try
        {
            $query = "INSERT INTO ShoppingListItem (ShoppingListId, Content, IsHandled) VALUES (:ShoppingListId, :Content, :IsHandled);";

            $this->db->BeginTransaction();

            foreach ($shoppingListItems as $shoppingListId => $value)
            {
                foreach ($value as $shoppingListItem)
                {
                    $params = [];

                    $params[":ShoppingListId"] = $shoppingListId;
                    $params[":Content"] = $shoppingListItem->GetContent();
                    $params[":IsHandled"] = $shoppingListItem->GetIsHandled() ? 1 : 0;

                    $this->db->Execute($query, $params);
                }
            }

            $this->db->Commit();
        }
        catch (\Exception $e)
        {
            $this->db->Rollback();

            ErrorManager::Manage($e);
        }
    }

    public function Delete(array $shoppingListIds = null) : void
    {
        try
        {
            $query = "DELETE SLI FROM ShoppingListItem AS SLI";

            $params = null;

            if ($shoppingListIds != null)
            {
                $params = [];
                $query .= " WHERE " . DALHelper::SetArrayParams($shoppingListIds, "SLI", "ShoppingListId", $params);
            }

            $query .= ";";

            $this->db->BeginTransaction();

            $this->db->Execute($query, $params);

            $this->db->Commit();
        }
        catch (\Exception $e)
        {
            $this->db->Rollback();

            ErrorManager::Manage($e);
        }
    }

    public function UpdateIsHandled(int $id, bool $value) : void
    {
        try
        {
            $query = "UPDATE ShoppingListItem SET IsHandled = :IsHandled WHERE Id = :Id;";

            $params = [];
            $params[":Id"] = $id;
            $params[":IsHandled"] = $value ? 1 : 0;

            $this->db->BeginTransaction();

            $this->db->Execute($query, $params);

            $this->db->Commit();
        }
        catch (\Exception $e)
        {
            $this->db->Rollback();

            ErrorManager::Manage($e);
        }
    }
}