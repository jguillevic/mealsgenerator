<?php

namespace Model\Recipe;

use Framework\Tools\Json\IJsonSerializable;
use Framework\Tools\Json\JsonHelper;

class Recipe implements IJsonSerializable
{
    private $id = -1;
    private $name = "";
    private $defaultPersonNumber = -1;
    private $preparationTime = -1;
    private $cookingTime = -1;
    private $instructions = [];
    private $ingredients = [];

    public function GetId() : int
    {
        return $this->id;
    }

    public function SetId(int $id) : Recipe
    {
        $this->id = $id;

        return $this;
    }

    public function GetName() : string
    {
        return $this->name;
    }

    public function SetName(string $name) : Recipe
    {
        $this->name = $name;

        return $this;
    }

    public function GetDefaultPersonNumber() : int
    {
        return $this->defaultPersonNumber;
    }

    public function SetDefaultPersonNumber(int $defaultPersonNumber) : Recipe
    {
        $this->defaultPersonNumber = $defaultPersonNumber;

        return $this;
    }

    public function GetPreparationTime() : int
    {
        return $this->preparationTime;
    }

    public function SetPreparationTime(int $preparationTime) : Recipe
    {
        $this->preparationTime = $preparationTime;

        return $this;
    }

    public function GetCookingTime() : int
    {
        return $this->cookingTime;
    }

    public function SetCookingTime(int $cookingTime) : Recipe
    {
        $this->cookingTime = $cookingTime;

        return $this;
    }

    public function GetInstructions() : array
    {
        return $this->instructions;
    }

    public function SetInstructions(array $instructions) : Recipe
    {
        $this->instructions = $instructions;

        return $this;
    }

    public function GetIngredients() : array
    {
        return $this->ingredients;
    }

    public function SetIngredients(array $ingredients) : Recipe
    {
        $this->ingredients = $ingredients;

        return $this;
    }

    public function SerializeToJson() : string
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