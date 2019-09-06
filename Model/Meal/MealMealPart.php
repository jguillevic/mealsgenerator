<?php

namespace Model\Meal;

use Framework\Tools\Json\IJsonSerializable;
use Model\Meal\MealPart;

class MealMealPart implements IJsonSerializable
{
    private $mealPart;

    public function GetMealPart()
    {
        return $this->mealPart;
    }

    public function SetMealPart($mealPart)
    {
        $this->mealPart = $mealPart;

        return $this;
    }

    public function SerializeToJson()
    {
        $json = "{\"MealPart\":" . $this->GetMealPart()->SerializeToJson()
            . "}";

        return $json;
    }

    public function SetFromStdClass($object)
    {
        $mealPart = (new MealPart())->SetFromStdClass($object->MealPart);
        $this->SetMealPart($mealPart);

        return $this;
    }
}