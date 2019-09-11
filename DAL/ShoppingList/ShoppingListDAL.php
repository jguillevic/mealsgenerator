<?php

namespace DAL\ShoppingList;

use Framework\DAL\Database;
use Framework\DAL\DALHelper;
use Framework\Tools\Error\ErrorManager;
use Model\ShoppingList\ShoppingList;
use DAL\ShoppingList\ShoppingListItemDAL;

class ShoppingListDAL
{
    private $db;
    
	public function __construct($db = null)
	{
		if (isset($db))
			$this->db = $db;
		else
			$this->db = new Database();
    }

    public function Load()
    {
        try
        {
            $query = "SELECT SL.Id
                    , SL.Name 
                    FROM ShoppingList SL;";

            $this->db->BeginTransaction();

            $rows = $this->db->Read($query);

            $shoppingLists = [];
            $shoppingListIds = [];

            foreach ($rows as $row)
            {
                $shoppingList = new ShoppingList();

                $shoppingList->SetId($row["Id"]);
                $shoppingList->SetName($row["Name"]);

                $shoppingListIds[] = $shoppingList->GetId();
                $shoppingLists[$shoppingList->GetId()] = $shoppingList;
            }

            $shoppingListItems= [];

            if (count($shoppingListIds) > 0)
            {
                $shoppingListItemDAL = new ShoppingListItemDAL($this->db);
                $shoppingListItems = $shoppingListItemDAL->Load($shoppingListIds);
            }

            $this->db->Commit();

            foreach ($shoppingLists as $id => $shoppingList)
            {
                if (array_key_exists($id, $shoppingListItems))
                    $shoppingList->SetItems($shoppingListItems[$id]);
            }

            return $shoppingLists;
        }
        catch (\Exception $e)
        {
            $this->db->Rollback();

            ErrorManager::Manage($e);
        }
    }

    public function Add($shoppingLists)
    {
        try
        {
            $query = "INSERT INTO ShoppingList (Name) VALUES (:Name);";

            $shoppingListItems = [];

            $this->db->BeginTransaction();

            foreach ($shoppingLists as $shoppingList)
            {
                $params = [];
                $params[":Name"] = $shoppingList->GetName();

                $this->db->Execute($query, $params);

                $shoppingListId = intval($this->db->GetLastInsertId()); 

                $shoppingListItems[$shoppingListId] = $shoppingList->GetItems();
            }

            $shoppingListItemDAL = new ShoppingListItemDAL($this->db);
            $shoppingListItemDAL->Add($shoppingListItems);

            $this->db->Commit();
        }
        catch (\Exception $e)
        {
            $this->db->Rollback();

            ErrorManager::Manage($e);
        }
    }

    public function Delete($ids = null)
    {
        try
        {
            $shoppingListItemDAL = new ShoppingListItemDAL($this->db);
            $shoppingListItemDAL->Delete($ids);

            $query = "DELETE SL FROM ShoppingList AS SL";

            $params = null;

            if ($ids != null)
            {
                $params = [];
                $query .= " WHERE " . DALHelper::SetArrayParams($ids, "SL", "Id", $params);
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
}