<?php

namespace Model\Meal;

use Framework\Tools\Json\IJsonSerializable;
use Framework\Tools\Json\JsonHelper;
use Model\Meal\MealMealKind;
use Model\Meal\MealMealItem;

class Meal implements IJsonSerializable
{
    private $id = -1;
    private $potentialKinds;
    private $items;

    public function __construct()
    {
        $potentialKinds;
        $items = [];
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

    public function GetItems()
    {
        return $this->items;
    }

    public function SetItems($items)
    {
        $this->items = $items;

        return $this;
    }

    public function SerializeToJson()
    {
        $json = "{\"Id\":" . $this->GetId()
            . ",\"PotentialKinds\":" . JsonHelper::SerializeArrayToJson($this->GetPotentialKinds())
            . ",\"Items\":" . JsonHelper::SerializeArrayToJson($this->GetItems())
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

        foreach ($object->Items as $value)
        {
            $item = (new MealMealItem())->SetFromStdClass($value);
            $this->GetItems()[] = $item;
        }

        return $this;
    }
}