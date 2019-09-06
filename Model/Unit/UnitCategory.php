<?php

namespace Model\Unit;

use Framework\Tools\Json\IJsonSerializable;

class UnitCategory implements IJsonSerializable
{
    private $id = -1;
    private $code;

    public function GetId()
    {
        return $this->id;
    }

    public function SetId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function GetCode()
    {
        return $this->code;
    }

    public function SetCode($code)
    {
        $this->code = $code;

        return $this;
    }

    public function SerializeToJson()
    {
        $json = "{\"Id\":" . $this->GetId()
            . ",\"Code\":\"" . $this->GetCode() . "\""
            . "}";

        return $json;
    }
}