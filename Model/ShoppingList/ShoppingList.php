<?php

namespace Model\ShoppingList;

class ShoppingList
{
    private $id = -1;
    private $name = "";
    private $items = [];

    public function GetId() : int
    {
        return $this->id;
    }

    public function SetId(int $id) : ShoppingList
    {
        $this->id = $id;

        return $this;
    }

    public function GetName() : string
    {
        return $this->name;
    }

    public function SetName(string $name) : ShoppingList
    {
        $this->name = $name;

        return $this;
    }

    public function GetItems() : array
    {
        return $this->items;
    }

    public function SetItems(array $items) : ShoppingList
    {
        $this->items = $items;

        return $this;
    }

    public function AddItems(array $items) : ShoppingList
    {
        foreach ($items as $item)
            $this->items[] = $item;

        return $this;
    }
}