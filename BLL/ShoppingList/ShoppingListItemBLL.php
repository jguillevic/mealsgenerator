<?php

namespace BLL\ShoppingList;

use DAL\ShoppingList\ShoppingListItemDAL;

class ShoppingListItemBLL
{
    private $shoppingListItemDAL;

	public function __construct()
	{
		$this->shoppingListItemDAL = new ShoppingListItemDAL();
    }

    public function UpdateIsHandled($id, $value)
    {
        $this->shoppingListItemDAL->UpdateIsHandled($id, $value === "true");
    }
}