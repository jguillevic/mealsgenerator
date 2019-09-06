<?php

namespace Model\Meal;

use Framework\Tools\Json\IJsonSerializable;
use Model\Meal\MealKind;

class MealMealKind implements IJsonSerializable
{
    private $kind;

    public function GetKind()
    {
        return $this->kind;
    }

    public function SetKind($kind)
    {
        $this->kind = $kind;

        return $this;
    }

    public function SerializeToJson()
    {
        $json = "{\"Kind\":" . $this->GetKind()->SerializeToJson()
            . "}";

        return $json;
    }

    public function SetFromStdClass($object)
    {
        $mealKind = (new MealKind())->SetFromStdClass($object->Kind);
        $this->SetKind($mealKind);

        return $this;
    }
}