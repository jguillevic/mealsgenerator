<?php

namespace BLL\ShoppingList;

use Framework\DAL\Database;
use Framework\Tools\Error\ErrorManager;
use DAL\ShoppingList\ShoppingListItemDAL;

class ShoppingListItemBLL
{
    public function UpdateIsHandled($id, $value)
    {
        try
        {
            $db = new Database();
            $db->BeginTransaction();

            $shoppingListItemDAL = new ShoppingListItemDAL($db);
            $shoppingListItemDAL->UpdateIsHandled($id, $value === "true");

            $db->Commit();
        }
        catch (\Exception $e)
        {
            if ($db != null)
                $db->Rollback();

            ErrorManager::Manage($e);
        }
    }
}