<?php

namespace DAL\Meal;

use Framework\DAL\Database;
use Framework\DAL\DALHelper;
use Framework\Tools\Error\ErrorManager;
use Model\Meal\MealMealItem;
use DAL\Meal\MealItemDAL;

class MealMealItemDAL
{
    private $db;
    
	public function __construct(Database $db = null)
	{
		if (isset($db))
			$this->db = $db;
		else
			$this->db = new Database();
    }

    public function Load(array $mealIds) : array
    {
        try
        {
            $query = "SELECT M_MP.MealId, M_MP.MealItemId FROM Meal_MealItem AS M_MP WHERE ";

            $params = [];
            $query .= DALHelper::SetArrayParams($mealIds, "M_MP", "MealId", $params);

            $query .= " ORDER BY M_MP.MealId;";

            $this->db->BeginTransaction();

            $rows = $this->db->Read($query, $params);

            $mealMealItems = [];
            $mealItemIds = [];

            foreach ($rows as $row)
            {
                $mealMealItem = new MealMealItem();

                $mealId = $row["MealId"];
                $mealItemId = $row["MealItemId"];

                $mealItemIds[] = $mealItemId;

                if (!array_key_exists($mealId, $mealMealItems))
                    $mealMealItems[$mealId] = [];

                $mealMealItems[$mealId][$mealItemId] = $mealMealItem;
            }

            $mealItemDAL = new MealItemDAL($this->db);
            $mealItems = $mealItemDAL->Load($mealItemIds);

            $this->db->Commit();

            foreach ($mealMealItems as $key1 => $value1)
            {
                foreach ($value1 as $key2 => $value2)
                {
                    $mealItem = $mealItems[$key2];
                    $value2->SetMealItem($mealItem);
                }
            }

            return $mealMealItems;
        }
        catch (\Exception $e)
        {
            $this->db->Rollback();

            ErrorManager::Manage($e);
        }
    }
}