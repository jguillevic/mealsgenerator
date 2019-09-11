<?php

namespace DAL\Meal;

use Framework\DAL\Database;
use Framework\DAL\DALHelper;
use Framework\Tools\Error\ErrorManager;
use Model\Meal\Meal;
use DAL\Meal\MealMealPartDAL;
use DAL\Meal\MealMealKindDAL;

class MealDAL
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
        try
        {
            $query = "SELECT M.Id
                    FROM Meal AS M";

            $params = null;

            if ($ids != null)
            {
                $params = [];
                $query .= " WHERE " . DALHelper::SetArrayParams($ids, "M", "Id", $params);
            }

            $this->db->BeginTransaction();

            $rows = $this->db->Read($query, $params);

            $meals = [];
            $mealIds = [];

            foreach ($rows as $row)
            {
                $meal = new Meal();
                $meal->SetId($row["Id"]);

                $mealIds[] = $meal->GetId();
                $meals[$meal->GetId()] = $meal;
            }

            $mealMealPartDAL = new MealMealPartDAL($this->db);
            $mealMealParts = $mealMealPartDAL->Load($mealIds);

            $mealMealKindDAL = new MealMealKindDAL($this->db);
            $mealMealKinds = $mealMealKindDAL->Load($mealIds);

            $this->db->Commit();
        
            foreach ($meals as $meal)
            {
                $parts = $mealMealParts[$meal->GetId()];
                $meal->SetParts($parts);

                $potentialKinds = $mealMealKinds[$meal->GetId()];
                $meal->SetPotentialKinds($potentialKinds);
            }

            return $meals;
        }
        catch (\Exception $e)
        {
            $this->db->Rollback();

            ErrorManager::Manage($e);
        }
    }
}