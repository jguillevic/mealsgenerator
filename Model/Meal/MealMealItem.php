<?php

namespace Model\Meal;

use Framework\Tools\Json\IJsonSerializable;
use Model\Meal\MealItem;

class MealMealItem implements IJsonSerializable
{
    private $mealItem;

    public function GetMealItem()
    {
        return $this->mealItem;
    }

    public function SetMealItem($mealItem)
    {
        $this->mealItem = $mealItem;

        return $this;
    }

    public function SerializeToJson()
    {
        $json = "{\"MealItem\":" . $this->GetMealItem()->SerializeToJson()
            . "}";

        return $json;
    }

    public function SetFromStdClass($object)
    {
        $mealItem = (new MealItem())->SetFromStdClass($object->MealItem);
        $this->SetMealItem($mealItem);

        return $this;
    }
}