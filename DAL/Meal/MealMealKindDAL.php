<?php

namespace DAL\Meal;

use Framework\DAL\Database;
use Framework\DAL\DALHelper;
use Framework\Tools\Error\ErrorManager;
use DAL\Meal\MealKindDAL;
use Model\Meal\MealMealKind;

class MealMealKindDAL
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
            $query = "SELECT M_MK.MealId
                    , M_MK.MealKindId
                    FROM Meal_MealKind AS M_MK
                    WHERE ";

            $params = [];
            $query .= DALHelper::SetArrayParams($mealIds, "M_MK", "MealId", $params);

            $query .= " ORDER BY M_MK.MealId;";

            $this->db->BeginTransaction();

            $rows = $this->db->Read($query, $params);

            $mealMealKinds = [];
            
            $mealKindDAL = new MealKindDAL($this->db);
            $mealKinds = $mealKindDAL->Load();

            $this->db->Commit();
        
            foreach ($rows as $row)
            {
                $mealMealKind = new MealMealKind();

                $mealId = $row["MealId"];
                $mealKindId = $row["MealKindId"];

                $mealMealKind->SetKind($mealKinds[$mealKindId]);

                if (!array_key_exists($mealId, $mealMealKinds))
                    $mealMealKinds[$mealId] = [];

                $mealMealKinds[$mealId][$mealKindId] = $mealMealKind;
            }

            return $mealMealKinds;
        }
        catch (\Exception $e)
        {
            $this->db->Rollback();

            ErrorManager::Manage($e);
        }
    }
}