<?php

namespace Model\Meal;

use Framework\Tools\Json\IJsonSerializable;

class PlannifiedMeal implements IJsonSerializable
{
    private $id = -1;
    private $date;
    private $personNumber;
    private $kind;
    private $meal;

    public function GetId()
    {
        return $this->id;
    }

    public function SetId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function GetDate()
    {
        return $this->date;
    }

    public function SetDate($date)
    {
        $this->date = $date;

        return $this;
    }

    public function GetPersonNumber()
    {
        return $this->personNumber;
    }

    public function SetPersonNumber($number)
    {
        $this->personNumber = $number;

        return $this;
    }

    public function GetKind()
    {
        return $this->kind;
    }

    public function SetKind($kind)
    {
        $this->kind = $kind;

        return $this;
    }

    public function GetMeal()
    {
        return $this->meal;
    }

    public function SetMeal($meal)
    {
        $this->meal = $meal;

        return $this;
    }

    public function SerializeToJson()
    {
        $json = "{\"Id\":" . $this->GetId() 
            . ",\"Date\":\"" . $this->GetDate()->format("Y-m-d") . "\""
            . ",\"PersonNumber\":" . $this->GetPersonNumber()
            . ",\"Kind\":" . $this->GetKind()->SerializeToJson()
            . ",\"Meal\":" . $this->GetMeal()->SerializeToJson()
            ."}";

        return $json;
    }

    public function SetFromStdClass($object)
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