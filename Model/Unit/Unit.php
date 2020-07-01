<?php

namespace Model\Unit;

use Model\Unit\MealCategory;
use Framework\Tools\Json\IJsonSerializable;

class Unit implements IJsonSerializable
{
    private $id = -1;
    private $name = "";
    private $code = "";
    private $conversionFactor = null;
    private $category = null;

    public function GetId() : int
    {
        return $this->id;
    }

    public function SetId($id) : Unit
    {
        $this->id = $id;

        return $this;
    }

    public function GetName() : string
    {
        return $this->name;
    }

    public function SetName(string $name) : Unit
    {
        $this->name = $name;

        return $this;
    }

    public function GetCode() : string
    {
        return $this->code;
    }

    public function SetCode(string $code) : Unit
    {
        $this->code = $code;

        return $this;
    }

    public function GetConversionFactor() : ?float
    {
        return $this->conversionFactor;
    }

    public function SetConversionFactor(?float $conversionFactor) : Unit
    {
        $this->conversionFactor = $conversionFactor;

        return $this;
    }

    public function GetCategory() : ?UnitCategory
    {
        return $this->category;
    }

    public function SetCategory(?UnitCategory $category) : Unit
    {
        $this->category = $category;

        return $this;
    }

    public function SerializeToJson() : string
    {
        $json = "{\"Id\":" . $this->GetId()
            . ",\"Name\":\"" . $this->GetName() . "\""
            . ",\"Code\":\"" . $this->GetCode() . "\""
            . ",\"ConversionFactor\":";
            
        $conversionFactor = $this->GetConversionFactor();
        if ($conversionFactor != null)
            $json .= str_replace(",", ".", $this->GetConversionFactor());
        else
            $json .= "null";

        $json .= ",\"Category\":" . $this->GetCategory()->SerializeToJson()
            . "}";

        return $json;
    }
}