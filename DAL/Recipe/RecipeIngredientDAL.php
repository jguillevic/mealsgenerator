<?php

namespace DAL\Recipe;

use Framework\DAL\Database;
use Framework\DAL\DALHelper;
use Framework\Tools\Error\ErrorManager;
use Model\Recipe\Recipe;
use Model\Recipe\RecipeIngredient;
use DAL\Recipe\IngredientDAL;
use DAL\Unit\UnitDAL;

class RecipeIngredientDAL
{
    private $db;
    
	public function __construct(Database $db = null)
	{
		if (isset($db))
			$this->db = $db;
		else
			$this->db = new Database();
    }

	public function Load(array $recipeIds) : array
	{
		try
		{
			$query = "SELECT R_I.RecipeId, R_I.IngredientId, R_I.Quantity, R_I.UnitId FROM Recipe_Ingredient AS R_I WHERE ";

			$params = [];
			$query .= DALHelper::SetArrayParams($recipeIds, "R_I", "RecipeId", $params);

			$query .= " ORDER BY R_I.RecipeId;";

			$this->db->BeginTransaction();

			$rows = $this->db->Read($query, $params);

			$unitDAL = new UnitDAL($this->db);
			$units = $unitDAL->Load();

			$recipeIngredients = [];
			$ingredientIds = [];

			foreach ($rows as $row)
			{
				$recipeIngredient = new RecipeIngredient();
				
				$recipeIngredient->SetQuantity($row["Quantity"]);
				$recipeIngredient->SetUnit($units[$row["UnitId"]]);

				$recipeId = $row["RecipeId"];
				$ingredientId = $row["IngredientId"];
				$ingredientIds[] = $ingredientId;

				if (!array_key_exists($recipeId, $recipeIngredients))
					$recipeIngredients[$recipeId] = [];

				$recipeIngredients[$recipeId][$ingredientId] = $recipeIngredient;
			}

			$ingredientDAL = new IngredientDAL($this->db);
			$ingredients = $ingredientDAL->Load($ingredientIds);

			$this->db->Commit();
		
			foreach ($recipeIngredients as $key1 => $value1)
			{
				foreach ($value1 as $key2 => $value2)
				{
					$ingredient = $ingredients[$key2];
					$value2->SetIngredient($ingredient);
				}
			}

			return $recipeIngredients;
		}
		catch (\Exception $e)
        {
			$this->db->Rollback();

            ErrorManager::Manage($e);
        }
	}
}