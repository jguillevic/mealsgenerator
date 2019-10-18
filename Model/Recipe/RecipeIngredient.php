<?php

namespace Model\Recipe;

use Model\Unit\Unit;
use Model\Recipe\Ingredient;
use Framework\Tools\Json\IJsonSerializable;

class RecipeIngredient implements IJsonSerializable
{
    private $ingredient = null;
    private $quantity = 0;
    private $unit = null;

    public function GetIngredient() : ?Ingredient
    {
        return $this->ingredient;
    }

    public function SetIngredient(?Ingredient $ingredient) : RecipeIngredient
    {
        $this->ingredient = $ingredient;

        return $this;
    }

    public function GetQuantity() : float
    {
        return $this->quantity;
    }

    public function SetQuantity(float $quantity) : RecipeIngredient
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function GetUnit() : ?Unit
    {
        return $this->unit;
    }

    public function SetUnit(?Unit $unit) : RecipeIngredient
    {
        $this->unit = $unit;

        return $this;
    }

    public function SerializeToJson() : string
    {
        $json = "{\"Ingredient\":" . $this->GetIngredient()->SerializeToJson()
            . ",\"Quantity\":" . str_replace(",", ".", $this->GetQuantity())
            . ",\"Unit\":" . $this->GetUnit()->SerializeToJson()
            . "}";

        return $json;
    }
}