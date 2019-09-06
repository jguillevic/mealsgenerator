<?php

namespace Model\Meal;

use Framework\Tools\Json\IJsonSerializable;
use Framework\Tools\Json\JsonHelper;
use Model\Meal\MealMealKind;
use Model\Meal\MealMealPart;

class Meal implements IJsonSerializable
{
    private $id = -1;
    private $potentialKinds;
    private $parts;

    public function __construct()
    {
        $potentialKinds;
        $parts = [];
    }

    public function GetId()
    {
        return $this->id;
    }

    public function SetId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function GetPotentialKinds()
    {
        return $this->potentialKinds;
    }

    public function SetPotentialKinds($kinds)
    {
        $this->potentialKinds = $kinds;

        return $this;
    }

    public function GetParts()
    {
        return $this->parts;
    }

    public function SetParts($parts)
    {
        $this->parts = $parts;

        return $this;
    }

    public function SerializeToJson()
    {
        $json = "{\"Id\":" . $this->GetId()
            . ",\"PotentialKinds\":" . JsonHelper::SerializeArrayToJson($this->GetPotentialKinds())
            . ",\"Parts\":" . JsonHelper::SerializeArrayToJson($this->GetParts())
            . "}";

        return $json;
    }

    public function SetFromStdClass($object)
    {
        $this->SetId($object->Id);

        foreach ($object->PotentialKinds as $value)
        {
            $potentialKind = (new MealMealKind())->SetFromStdClass($value);
            $this->GetPotentialKinds()[] = $potentialKind;
        }

        foreach ($object->Parts as $value)
        {
            $part = (new MealMealPart())->SetFromStdClass($value);
            $this->GetParts()[] = $part;
        }

        return $this;
    }
}