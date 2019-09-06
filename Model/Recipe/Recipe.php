<?php

namespace Model\Recipe;

use Framework\Tools\Json\IJsonSerializable;
use Framework\Tools\Json\JsonHelper;

class Recipe implements IJsonSerializable
{
    private $id = -1;
    private $name;
    private $defaultPersonNumber;
    private $preparationTime;
    private $cookingTime;
    private $instructions;
    private $ingredients;

    public function __construct()
    {
        $instructions = [];
        $ingredients = [];
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

    public function GetName()
    {
        return $this->name;
    }

    public function SetName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function GetDefaultPersonNumber()
    {
        return $this->defaultPersonNumber;
    }

    public function SetDefaultPersonNumber($defaultPersonNumber)
    {
        $this->defaultPersonNumber = $defaultPersonNumber;

        return $this;
    }

    public function GetPreparationTime()
    {
        return $this->preparationTime;
    }

    public function SetPreparationTime($preparationTime)
    {
        $this->preparationTime = $preparationTime;

        return $this;
    }

    public function GetCookingTime()
    {
        return $this->cookingTime;
    }

    public function SetCookingTime($cookingTime)
    {
        $this->cookingTime = $cookingTime;

        return $this;
    }

    public function GetInstructions()
    {
        return $this->instructions;
    }

    public function SetInstructions($instructions)
    {
        $this->instructions = $instructions;

        return $this;
    }

    public function GetIngredients()
    {
        return $this->ingredients;
    }

    public function SetIngredients($ingredients)
    {
        $this->ingredients = $ingredients;

        return $this;
    }

    public function SerializeToJson()
    {
        $json = "{\"Id\":" . $this->GetId()
            . ",\"Name\":\"" . $this->GetName() . "\""
            . ",\"DefaultPersonNumber\":" . $this->GetDefaultPersonNumber()
            . ",\"PreparationTime\":" . $this->GetPreparationTime()
            . ",\"CookingTime\":" . $this->GetCookingTime()
            . ",\"Instructions\":" . JsonHelper::SerializeArrayToJson($this->GetInstructions())
            . ",\"Ingredients\":" . JsonHelper::SerializeArrayToJson($this->GetIngredients())
            . "}";

        return $json;
    }
}