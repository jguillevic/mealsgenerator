<?php

namespace Model\Meal;

use Model\Meal\Meal;
use Model\Meal\MealKind;
use Framework\Tools\Json\IJsonSerializable;

class PlannifiedMeal implements IJsonSerializable
{
    private $id = -1;
    private $date = null;
    private $personNumber = -1;
    private $kind = null;
    private $meal = null;

    public function GetId() : int
    {
        return $this->id;
    }

    public function SetId(int $id) : PlannifiedMeal
    {
        $this->id = $id;

        return $this;
    }

    public function GetDate() : \DateTime
    {
        return $this->date;
    }

    public function SetDate(\DateTime $date) : PlannifiedMeal
    {
        $this->date = $date;

        return $this;
    }

    public function GetPersonNumber() : int
    {
        return $this->personNumber;
    }

    public function SetPersonNumber(int $number) : PlannifiedMeal
    {
        $this->personNumber = $number;

        return $this;
    }

    public function GetKind() : ?MealKind
    {
        return $this->kind;
    }

    public function SetKind(?MealKind $kind) : PlannifiedMeal
    {
        $this->kind = $kind;

        return $this;
    }

    public function GetMeal() : ?Meal
    {
        return $this->meal;
    }

    public function SetMeal(?Meal $meal) : PlannifiedMeal
    {
        $this->meal = $meal;

        return $this;
    }

    public function SerializeToJson() : string
    {
        $json = "{\"Id\":" . $this->GetId() 
            . ",\"Date\":\"" . $this->GetDate()->format("Y-m-d") . "\""
            . ",\"PersonNumber\":" . $this->GetPersonNumber()
            . ",\"Kind\":" . $this->GetKind()->SerializeToJson()
            . ",\"Meal\":" . $this->GetMeal()->SerializeToJson()
            ."}";

        return $json;
    }

    public function SetFromStdClass(\stdClass $object) : PlannifiedMeal
    {
        $this->SetId($object->Id);
        $this->SetDate(new \DateTime($object->Date));
        $this->SetPersonNumber($object->PersonNumber);
        $kind = (new MealKind())->SetFromStdClass($object->Kind);
        $this->SetKind($kind);
        $meal = (new Meal())->SetFromStdClass($object->Meal);
        $this->SetMeal($meal);

        return $this;
    }
}