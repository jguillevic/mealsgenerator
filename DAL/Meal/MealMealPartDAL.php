<?php

namespace DAL\Meal;

use Framework\DAL\Database;
use Framework\DAL\DALHelper;
use Framework\Tools\Error\ErrorManager;
use Model\Meal\MealMealPart;
use DAL\Meal\MealPartDAL;

class MealMealPartDAL
{
    private $db;
    
	public function __construct($db = null)
	{
		if (isset($db))
			$this->db = $db;
		else
			$this->db = new Database();
    }

    public function Load($mealIds)
    {
        try
        {
            $query = "SELECT M_MP.MealId, M_MP.MealPartId FROM Meal_MealPart AS M_MP WHERE ";

            $params = [];
            $query .= DALHelper::SetArrayParams($mealIds, "M_MP", "MealId", $params);

            $query .= " ORDER BY M_MP.MealId;";

            $rows = $this->db->Read($query, $params);

            $mealMealParts = [];
            $mealPartIds = [];

            foreach ($rows as $row)
            {
                $mealMealPart = new MealMealPart();

                $mealId = $row["MealId"];
                $mealPartId = $row["MealPartId"];

                $mealPartIds[] = $mealPartId;

                if (!array_key_exists($mealId, $mealMealParts))
                    $mealMealParts[$mealId] = [];

                $mealMealParts[$mealId][$mealPartId] = $mealMealPart;
            }

            $mealPartDAL = new MealPartDAL($this->db);
            $mealParts = $mealPartDAL->Load($mealPartIds);

            foreach ($mealMealParts as $key1 => $value1)
            {
                foreach ($value1 as $key2 => $value2)
                {
                    $mealPart = $mealParts[$key2];
                    $value2->SetMealPart($mealPart);
                }
            }

            return $mealMealParts;
        }
        catch (\Exception $e)
        {
            ErrorManager::Manage($e);
        }
    }
}