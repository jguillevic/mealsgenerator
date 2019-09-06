<?php

namespace Model\ShoppingList;

class ShoppingList
{
    private $id = -1;
    private $name;
    private $items;

    public function __construct()
    {
        $items = [];
    }

    public function GetId()
    {
        return $this->id;
    }

    public function SetId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function GetName()
    {
        return $this->name;
    }

    public function SetName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function GetItems()
    {
        return $this->items;
    }

    public function SetItems($items)
    {
        $this->items = $items;

        return $this;
    }

    public function AddItems($items)
    {
        foreach ($items as $item)
            $this->items[] = $item;
    }
}