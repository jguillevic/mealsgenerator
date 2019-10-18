<?php

namespace Model\ShoppingList;

class ShoppingListItem
{
    private $id = -1;
    private $content = "";
    private $isHandled = false;

    public function GetId() : int
    {
        return $this->id;
    }

    public function SetId(int $id) : ShoppingListItem
    {
        $this->id = $id;

        return $this;
    }

    public function GetContent() : string
    {
        return $this->content;
    }

    public function SetContent(string $content) : ShoppingListItem
    {
        $this->content = $content;

        return $this;
    }

    public function GetIsHandled() : bool
    {
        return $this->isHandled;
    }

    public function SetIsHandled(bool $isHandled) : ShoppingListItem
    {
        $this->isHandled = $isHandled;

        return $this;
    }
}