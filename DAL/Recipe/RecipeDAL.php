<?php

namespace DAL\Recipe;

use Framework\DAL\Database;
use Framework\DAL\DALHelper;
use Model\Recipe\Recipe;
use DAL\Recipe\RecipeIngredientDAL;
use DAL\Recipe\InstructionDAL;

class RecipeDAL
{
    private $db;
    
	public function __construct($db = null)
	{
		if (isset($db))
			$this->db = $db;
		else
			$this->db = new Database();
    }

    public function Load($ids = null)
    {
        $query = "SELECT R.Id
                  , R.Name
                  , R.DefaultPersonNumber
                  , R.PreparationTime
                  , R.CookingTime
                  FROM Recipe AS R";

        $params = null;

        if ($ids != null)
        {

            $params = [];
            $query .= " WHERE " . DALHelper::SetArrayParams($ids, "R", "Id", $params);
        }

        $query .= ";";

        $rows = $this->db->Read($query, $params);

        $recipes = [];
        $recipeIds = [];

        foreach ($rows as $row)
        {
            $recipe = new Recipe();
            $recipe->SetId($row["Id"]);
            $recipe->SetName($row["Name"]);
            $recipe->SetDefaultPersonNumber($row["DefaultPersonNumber"]);
            $recipe->SetPreparationTime($row["PreparationTime"]);
            $recipe->SetCookingTime($row["CookingTime"]);

            $recipeIds[] = $recipe->GetId();

            $recipes[$recipe->GetId()] = $recipe;
        }

        // Chargement des instructions.
        $instructionDAL = new InstructionDAL($this->db);
        $instructions = $instructionDAL->Load($recipeIds);

        // Chargement des ingrédients.
        $recipeIngredientDAL = new RecipeIngredientDAL($this->db);
        $ingredients = $recipeIngredientDAL->Load($recipeIds);

        // Affectation des instructions/ingrédients aux recettes précédemment chargées.
        foreach ($recipes as $recipeId => $recipe)
        {
            $recipe->SetInstructions($instructions[$recipeId]);
            $recipe->SetIngredients($ingredients[$recipeId]);
        }

        return $recipes;
    }
}