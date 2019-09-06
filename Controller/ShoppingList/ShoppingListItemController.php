<?php

namespace Controller\ShoppingList;

use Framework\View\View;
use Framework\Tools\Helper\RoutesHelper;
use Framework\Tools\Helper\PathHelper;
use BLL\ShoppingList\ShoppingListItemBLL;

class ShoppingListItemController
{
    public function UpdateIsHandled($queryParameters)
    {
        if ($_SERVER["REQUEST_METHOD"] == "GET")
        {
            $id = $queryParameters["Id"]->GetValue();
            $value = $queryParameters["Value"]->GetValue();

            $shoppingListItemBLL = new ShoppingListItemBLL();
            $shoppingListItemBLL->UpdateIsHandled($id, $value);
        }
        else
            RoutesHelper::Redirect("DisplayError");
    }
}