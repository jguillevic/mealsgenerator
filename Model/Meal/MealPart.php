<?php

namespace Model\Meal;

use Framework\Tools\Json\IJsonSerializable;
use Model\Recipe\Recipe;

class MealPart implements IJsonSerializable
{
    private $id = -1;
    private $name;
    private $weekProposedMaxCount;
    private $recipe;

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

    public function GetWeekProposedMaxCount()
    {
        return $this->weekProposedMaxCount;
    }

    public function SetWeekProposedMaxCount($weekProposedMaxCount)
    {
        $this->weekProposedMaxCount = $weekProposedMaxCount;
    }

    public function GetRecipe()
    {
        return $this->recipe;
    }

    public function SetRecipe($recipe)
    {
        $this->recipe = $recipe;

        return $this;
    }
    
    public function SerializeToJson()
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

    public function SetFromStdClass($object)
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