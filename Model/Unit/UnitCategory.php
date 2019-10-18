<?php

namespace Model\Unit;

use Framework\Tools\Json\IJsonSerializable;

class UnitCategory implements IJsonSerializable
{
    private $id = -1;
    private $code = "";

    public function GetId() : int
    {
        return $this->id;
    }

    public function SetId(int $id) : UnitCategory
    {
        $this->id = $id;

        return $this;
    }

    public function GetCode() : string
    {
        return $this->code;
    }

    public function SetCode(string $code) : UnitCategory
    {
        $this->code = $code;

        return $this;
    }

    public function SerializeToJson() : string
    {
        $json = "{\"Id\":" . $this->GetId()
            . ",\"Code\":\"" . $this->GetCode() . "\""
            . "}";

        return $json;
    }
}