<?php

namespace Model\Meal;

use Model\Meal\MealItem;
use Framework\Tools\Json\IJsonSerializable;

class MealMealItem implements IJsonSerializable
{
    private $mealItem = null;

    public function GetMealItem() : ?MealItem
    {
        return $this->mealItem;
    }

    public function SetMealItem(?MealItem $mealItem) : MealMealItem
    {
        $this->mealItem = $mealItem;

        return $this;
    }

    public function SerializeToJson() : string
    {
        $json = "{\"MealItem\":" . $this->GetMealItem()->SerializeToJson()
            . "}";

        return $json;
    }

    public function SetFromStdClass(\stdClass $object) : MealMealItem
    {
        $mealItem = (new MealItem())->SetFromStdClass($object->MealItem);
        $this->SetMealItem($mealItem);

        return $this;
    }
}