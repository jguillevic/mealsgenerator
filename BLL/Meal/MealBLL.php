<?php

namespace BLL\Meal;

use Framework\DAL\Database;
use Framework\Tools\Error\ErrorManager;
use DAL\Meal\MealDAL;

class MealBLL
{  
    public function Load() : array
    {
        try
        {
            $db = new Database();
            $db->BeginTransaction();

            $mealDAL = new MealDAL($db);
            $meals = $mealDAL->Load();

            $db->Commit();

            return $meals;
        }
        catch (\Exception $e)
        {
            if ($db != null)
                $db->Rollback();

            ErrorManager::Manage($e);
        }
    }
}