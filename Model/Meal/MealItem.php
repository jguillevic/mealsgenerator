<?php

namespace Model\Meal;

use Model\Recipe\Recipe;
use Framework\Tools\Json\IJsonSerializable;

class MealItem implements IJsonSerializable
{
    private $id = -1;
    private $name = "";
    private $weekProposedMaxCount = -1;
    private $recipe = null;

    public function GetId() : int
    {
        return $this->id;
    }

    public function SetId(int $id) : MealItem
    {
        $this->id = $id;

        return $this;
    }

    public function GetName() : string
    {
        return $this->name;
    }

    public function SetName(string $name) : MealItem
    {
        $this->name = $name;

        return $this;
    }

    public function GetWeekProposedMaxCount() : int
    {
        return $this->weekProposedMaxCount;
    }

    public function SetWeekProposedMaxCount(int $weekProposedMaxCount) : MealItem
    {
        $this->weekProposedMaxCount = $weekProposedMaxCount;

        return $this;
    }

    public function GetRecipe() : ?Recipe
    {
        return $this->recipe;
    }

    public function SetRecipe(?Recipe $recipe) : MealItem
    {
        $this->recipe = $recipe;

        return $this;
    }
    
    public function SerializeToJson() : string
    {
        $json = "{\"Id\":" . $this->GetId()
            . ",\"Name\":\"" . $this->GetName() . "\""
            . ",\"WeekProposedMaxCount\":" . $this->GetWeekProposedMaxCount()
            . ",\"Recipe\":";

        if ($this->GetRecipe() != null)
            $json .= $this->GetRecipe()->SerializeToJson();
        else
            $json .= "null";

        $json .= "}";

        return $json;
    }

    public function SetFromStdClass(\stdClass $object) : MealItem
    {
        $this->SetId($object->Id);
        $this->SetName($object->Name);
        $this->SetWeekProposedMaxCount($object->WeekProposedMaxCount);
        // if ($object->Recipe != null)
        // {
        //     $recipe = (new Recipe())->SetFromStdClass($object->Recipe);
        //     $this->SetRecipe($recipe);
        // }

        return $this;
    }
}