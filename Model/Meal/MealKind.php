<?php

namespace Model\Meal;

use Framework\Tools\Json\IJsonSerializable;

class MealKind implements IJsonSerializable
{
    const BREAKFAST = "BREAKFAST";
    const LUNCH = "LUNCH";
    const DINNER = "DINNER";

    private $id = -1;
    private $code;
    private $name;

    public function GetId()
    {
        return $this->id;
    }

    public function Setid($id)
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

    public function GetName()
    {
        return $this->name;
    }

    public function SetName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function SerializeToJson()
    {
        $json = "{\"Id\":" . $this->GetId() 
            . ",\"Code\":\"" . $this->GetCode() . "\""
            . ",\"Name\":\"" . $this->GetName() . "\""
            . "}";

        return $json;
    }

    public function SetFromStdClass($object)
    {
        $this->SetId($object->Id);
        $this->SetCode($object->Code);
        $this->SetName($object->Name);

        return $this;
    }
}