<?php

namespace Model\ShoppingList;

class ShoppingListItem
{
    private $id = -1;
    private $content;
    private $isHandled = false;

    public function GetId()
    {
        return $this->id;
    }

    public function SetId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function GetContent()
    {
        return $this->content;
    }

    public function SetContent($content)
    {
        $this->content = $content;

        return $this;
    }

    public function GetIsHandled()
    {
        return $this->isHandled;
    }

    public function SetIsHandled($isHandled)
    {
        $this->isHandled = $isHandled;

        return $this;
    }
}