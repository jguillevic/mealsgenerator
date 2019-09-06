<?php

namespace Model\Recipe;

use Framework\Tools\Json\IJsonSerializable;

class Ingredient implements IJsonSerializable
{
    private $id = -1;
    private $name;
    private $defaultUnit;

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

    public function GetDefaultUnit()
    {
        return $this->defaultUnit;
    }

    public function SetDefaultUnit($defaultUnit)
    {
        $this->defaultUnit = $defaultUnit;

        return $this;
    }

    public function SerializeToJson()
    {
        $json = "{\"Id\":" . $this->GetId()
            . ",\"Name\":\"" . $this->GetName() . "\""
            . ",\"DefaultUnit\":" . $this->GetDefaultUnit()->SerializeToJson()
            . "}";

        return $json;
    }
}