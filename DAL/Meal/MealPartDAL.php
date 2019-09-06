<?php

namespace DAL\Meal;

use Framework\DAL\Database;
use Framework\DAL\DALHelper;
use Model\Meal\MealPart;
use DAL\Recipe\RecipeDAL;

class MealPartDAL
{
    private $db;
    
	public function __construct($db = null)
	{
		if (isset($db))
			$this->db = $db;
		else
			$this->db = new Database();
    }

    public function Load($mealPartIds)
    {
        $query = "SELECT MP.Id, MP.Name, MP.WeekProposedMaxCount, MP.RecipeId FROM MealPart AS MP WHERE ";

        $params = [];
        $query .= DALHelper::SetArrayParams($mealPartIds, "MP", "Id", $params);

        $rows = $this->db->Read($query, $params);

        $mealParts = [];
        $recipeIds = [];

        foreach ($rows as $row)
        {
            $mealPart = new MealPart();
            $mealPart->SetId($row["Id"]);
            $mealPart->SetName($row["Name"]);
            $mealPart->SetWeekProposedMaxCount($row["WeekProposedMaxCount"]);

            $recipeId = $row["RecipeId"];
            if ($recipeId != null)
                $recipeIds[$mealPart->GetId()] = $row["RecipeId"];

            $mealParts[$mealPart->GetId()] = $mealPart;
        }

        $recipeDAL = new RecipeDAL($this->db);
        $recipes = $recipeDAL->Load($recipeIds);

        foreach ($recipeIds as $mealPartId => $recipeId)
        {
            $recipe = $recipes[$recipeId];
            $mealParts[$mealPartId]->SetRecipe($recipe);
        }

        return $mealParts;
    }
}