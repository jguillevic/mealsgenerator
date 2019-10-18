<?php

namespace Model\Meal;

use Framework\Tools\Json\IJsonSerializable;

class MealKind implements IJsonSerializable
{
    const BREAKFAST_CODE = "BREAKFAST";
    const LUNCH_CODE = "LUNCH";
    const DINNER_CODE = "DINNER";

    private $id = -1;
    private $code = "";
    private $name = "";

    public function GetId() : int
    {
        return $this->id;
    }

    public function Setid(int $id) : MealKind
    {
        $this->id = $id;

        return $this;
    }

    public function GetCode() : string
    {
        return $this->code;
    }

    public function SetCode(string $code) : MealKind
    {
        $this->code = $code;

        return $this;
    }

    public function GetName() : string
    {
        return $this->name;
    }

    public function SetName(string $name) : MealKind
    {
        $this->name = $name;

        return $this;
    }

    public function SerializeToJson() : string
    {
        $json = "{\"Id\":" . $this->GetId() 
            . ",\"Code\":\"" . $this->GetCode() . "\""
            . ",\"Name\":\"" . $this->GetName() . "\""
            . "}";

        return $json;
    }

    public function SetFromStdClass(\stdClass $object) : MealKind
    {
        $this->SetId($object->Id);
        $this->SetCode($object->Code);
        $this->SetName($object->Name);

        return $this;
    }
}