<?php

namespace Model\Recipe;

use Model\Unit\Unit;
use Framework\Tools\Json\IJsonSerializable;

class Ingredient implements IJsonSerializable
{
    private $id = -1;
    private $name = "";
    private $defaultUnit = null;

    public function GetId() : int
    {
        return $this->id;
    }

    public function SetId(int $id) : Ingredient
    {
        $this->id = $id;

        return $this;
    }

    public function GetName() : string
    {
        return $this->name;
    }

    public function SetName(string $name) : Ingredient
    {
        $this->name = $name;

        return $this;
    }

    public function GetDefaultUnit() : ?Unit
    {
        return $this->defaultUnit;
    }

    public function SetDefaultUnit(?Unit $defaultUnit) : Ingredient
    {
        $this->defaultUnit = $defaultUnit;

        return $this;
    }

    public function SerializeToJson() : string
    {
        $json = "{\"Id\":" . $this->GetId()
            . ",\"Name\":\"" . $this->GetName() . "\""
            . ",\"DefaultUnit\":" . $this->GetDefaultUnit()->SerializeToJson()
            . "}";

        return $json;
    }
}