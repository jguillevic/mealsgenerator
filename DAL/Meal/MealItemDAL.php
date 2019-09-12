<?php

namespace DAL\Meal;

use Framework\DAL\Database;
use Framework\DAL\DALHelper;
use Framework\Tools\Error\ErrorManager;
use Model\Meal\MealItem;
use DAL\Recipe\RecipeDAL;

class MealItemDAL
{
    private $db;
    
	public function __construct($db = null)
	{
		if (isset($db))
			$this->db = $db;
		else
			$this->db = new Database();
    }

    public function Load($mealItemIds)
    {
        try
        {
            $query = "SELECT MP.Id, MP.Name, MP.WeekProposedMaxCount, MP.RecipeId FROM MealItem AS MP WHERE ";

            $params = [];
            $query .= DALHelper::SetArrayParams($mealItemIds, "MP", "Id", $params);

            $this->db->BeginTransaction();

            $rows = $this->db->Read($query, $params);

            $mealItems = [];
            $recipeIds = [];

            foreach ($rows as $row)
            {
                $mealItem = new MealItem();
                $mealItem->SetId($row["Id"]);
                $mealItem->SetName($row["Name"]);
                $mealItem->SetWeekProposedMaxCount($row["WeekProposedMaxCount"]);

                $recipeId = $row["RecipeId"];
                if ($recipeId != null)
                    $recipeIds[$mealItem->GetId()] = $row["RecipeId"];

                $mealItems[$mealItem->GetId()] = $mealItem;
            }

            $recipeDAL = new RecipeDAL($this->db);
            $recipes = $recipeDAL->Load($recipeIds);

            foreach ($recipeIds as $mealItemId => $recipeId)
            {
                $recipe = $recipes[$recipeId];
                $mealItems[$mealItemId]->SetRecipe($recipe);
            }

            $this->db->Commit();

            return $mealItems;
        }
        catch (\Exception $e)
        {
            $this->db->Rollback();

            ErrorManager::Manage($e);
        }
    }
}