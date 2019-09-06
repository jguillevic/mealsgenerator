<?php

namespace DAL\ShoppingList;

use Framework\DAL\Database;
use Framework\DAL\DALHelper;
use Model\ShoppingList\ShoppingListItem;

class ShoppingListItemDAL
{
    private $db;
    
	public function __construct($db = null)
	{
		if (isset($db))
			$this->db = $db;
		else
			$this->db = new Database();
    }

    public function Load($shoppingListIds)
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

        $rows = $this->db->Read($query, $params);

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

    public function Add($shoppingListItems)
    {
        $query = "INSERT INTO ShoppingListItem (ShoppingListId, Content, IsHandled) VALUES (:ShoppingListId, :Content, :IsHandled);";

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
    }

    public function Delete($shoppingListIds = null)
    {
        $query = "DELETE SLI FROM ShoppingListItem AS SLI";

        $params = null;

        if ($shoppingListIds != null)
        {
            $params = [];
            $query .= " WHERE " . DALHelper::SetArrayParams($shoppingListIds, "SLI", "ShoppingListId", $params);
        }

        $query .= ";";

        $this->db->Execute($query, $params);
    }

    public function UpdateIsHandled($id, $value)
    {
        $query = "UPDATE ShoppingListItem SET IsHandled = :IsHandled WHERE Id = :Id;";

        $params = [];
        $params[":Id"] = $id;
        $params[":IsHandled"] = $value ? 1 : 0;

        $this->db->Execute($query, $params);
    }
}