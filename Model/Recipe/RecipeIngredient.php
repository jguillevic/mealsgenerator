<?php

namespace Model\Recipe;

use Framework\Tools\Json\IJsonSerializable;

class RecipeIngredient implements IJsonSerializable
{
    private $ingredient;
    private $quantity;
    private $unit;

    public function GetIngredient()
    {
        return $this->ingredient;
    }

    public function SetIngredient($ingredient)
    {
        $this->ingredient = $ingredient;

        return $this;
    }

    public function GetQuantity()
    {
        return $this->quantity;
    }

    public function SetQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function GetUnit()
    {
        return $this->unit;
    }

    public function SetUnit($unit)
    {
        $this->unit = $unit;

        return $this;
    }

    public function SerializeToJson()
    {
        $json = "{\"Ingredient\":" . $this->GetIngredient()->SerializeToJson()
            . ",\"Quantity\":" . $this->GetQuantity()
            . ",\"Unit\":" . $this->GetUnit()->SerializeToJson()
            . "}";

        return $json;
    }
}