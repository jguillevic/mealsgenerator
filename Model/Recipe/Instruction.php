<?php

namespace Model\Recipe;

use Framework\Tools\Json\IJsonSerializable;

class Instruction implements IJsonSerializable
{
    private $id = -1;
    private $order = -1;
    private $content = "";

    public function GetId() : int
    {
        return $this->id;
    }

    public function SetId(int $id) : Instruction
    {
        $this->id = $id;

        return $this;
    }

    public function GetOrder() : int
    {
        return $this->order;
    }

    public function SetOrder(int $order) : Instruction
    {
        $this->order = $order;

        return $this;
    }

    public function GetContent() : string
    {
        return $this->content;
    }

    public function SetContent(string $content) : Instruction
    {
        $this->content = $content;

        return $this;
    }

    public function SerializeToJson() : string
    {
        $json = "{\"Id\":" . $this->GetId()
            . ",\"Order\":" . $this->GetOrder()
            . ",\"Content\":\"" . $this->GetContent() . "\""
            . "}";

        return $json;
    }
}