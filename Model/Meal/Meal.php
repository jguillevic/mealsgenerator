<?php

namespace Model\Meal;

use Framework\Tools\Json\IJsonSerializable;
use Framework\Tools\Json\JsonHelper;
use Model\Meal\MealMealKind;
use Model\Meal\MealMealItem;

class Meal implements IJsonSerializable
{
    private $id = -1;
    private $potentialKinds = [];
    private $items = [];

    public function GetId() : int
    {
        return $this->id;
    }

    public function SetId(int $id) : Meal
    {
        $this->id = $id;

        return $this;
    }

    public function GetPotentialKinds() : array
    {
        return $this->potentialKinds;
    }

    public function SetPotentialKinds(array $kinds) : Meal
    {
        $this->potentialKinds = $kinds;

        return $this;
    }

    public function GetItems() : array
    {
        return $this->items;
    }

    public function SetItems(array $items) : Meal
    {
        $this->items = $items;

        return $this;
    }

    public function SerializeToJson() : string
    {
        $json = "{\"Id\":" . $this->GetId()
            . ",\"PotentialKinds\":" . JsonHelper::SerializeArrayToJson($this->GetPotentialKinds())
            . ",\"Items\":" . JsonHelper::SerializeArrayToJson($this->GetItems())
            . "}";

        return $json;
    }

    public function SetFromStdClass(\stdClass $object) : Meal
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