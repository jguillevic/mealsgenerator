<?php

namespace DAL\Meal;

use Framework\DAL\Database;
use Framework\DAL\DALHelper;
use Framework\Tools\Error\ErrorManager;
use Model\Meal\Meal;
use DAL\Meal\MealMealItemDAL;
use DAL\Meal\MealMealKindDAL;

class MealDAL
{
    private $db;
    
	public function __construct(Database $db = null)
	{
		if (isset($db))
			$this->db = $db;
		else
			$this->db = new Database();
    }

    public function Load(array $ids = null) : array
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

            $mealMealItemDAL = new MealMealItemDAL($this->db);
            $mealMealItems = $mealMealItemDAL->Load($mealIds);

            $mealMealKindDAL = new MealMealKindDAL($this->db);
            $mealMealKinds = $mealMealKindDAL->Load($mealIds);

            $this->db->Commit();
        
            foreach ($meals as $meal)
            {
                $items = $mealMealItems[$meal->GetId()];
                $meal->SetItems($items);

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