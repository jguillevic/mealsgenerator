<?php

namespace Model\Recipe;

use Framework\Tools\Json\IJsonSerializable;

class Instruction implements IJsonSerializable
{
    private $id = -1;
    private $order;
    private $content;

    public function GetId()
    {
        return $this->id;
    }

    public function SetId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function GetOrder()
    {
        return $this->order;
    }

    public function SetOrder($order)
    {
        $this->order = $order;

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

    public function SerializeToJson()
    {
        $json = "{\"Id\":" . $this->GetId()
            . ",\"Order\":" . $this->GetOrder()
            . ",\"Content\":\"" . $this->GetContent() . "\""
            . "}";

        return $json;
    }
}