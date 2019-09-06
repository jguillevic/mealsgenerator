<?php

namespace Model\Unit;

use Framework\Tools\Json\IJsonSerializable;

class Unit implements IJsonSerializable
{
    private $id = -1;
    private $name;
    private $code;
    private $conversionFactor;
    private $category;

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

    public function GetCode()
    {
        return $this->code;
    }

    public function SetCode($code)
    {
        $this->code = $code;

        return $this;
    }

    public function GetConversionFactor()
    {
        return $this->conversionFactor;
    }

    public function SetConversionFactor($conversionFactor)
    {
        $this->conversionFactor = $conversionFactor;

        return $this;
    }

    public function GetCategory()
    {
        return $this->category;
    }

    public function SetCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    public function SerializeToJson()
    {
        $json = "{\"Id\":" . $this->GetId()
            . ",\"Name\":\"" . $this->GetName() . "\""
            . ",\"Code\":\"" . $this->GetCode() . "\""
            . ",\"ConversionFactor\":";
            
        $conversionFactor = $this->GetConversionFactor();
        if ($conversionFactor != null)
            $json .= $this->GetConversionFactor();
        else
            $json .= "null";

        $json .= ",\"Category\":" . $this->GetCategory()->SerializeToJson()
            . "}";

        return $json;
    }
}