<?php

namespace Model\Meal;

use Model\Meal\MealKind;
use Framework\Tools\Json\IJsonSerializable;

class MealMealKind implements IJsonSerializable
{
    private $kind = null;

    public function GetKind() : ?MealKind
    {
        return $this->kind;
    }

    public function SetKind(?MealKind $kind) : MealMealKind
    {
        $this->kind = $kind;

        return $this;
    }

    public function SerializeToJson() : string
    {
        $json = "{\"Kind\":" . $this->GetKind()->SerializeToJson()
            . "}";

        return $json;
    }

    public function SetFromStdClass(\stdClass $object) : MealMealKind
    {
        $mealKind = (new MealKind())->SetFromStdClass($object->Kind);
        $this->SetKind($mealKind);

        return $this;
    }
}