<?php

namespace DAL\Meal;

use Framework\DAL\Database;
use Model\Meal\PlannifiedMeal;
use Framework\Tools\Error\ErrorManager;
use DAL\Meal\MealKindDAL;
use DAL\Meal\MealDAL;

class PlannifiedMealDAL
{
    private $db;
    
	public function __construct(Database $db = null)
	{
		if (isset($db))
			$this->db = $db;
		else
			$this->db = new Database();
    }

    public function Load(\DateTime $startingDate, \DateTime $endingDate) : array
    {
        try
        {
            $query = "SELECT PM.Id
                    , PM.Date
                    , PM.PersonNumber
                    , PM.KindId
                    , PM.MealId
                    FROM PlannifiedMeal AS PM
                    WHERE PM.Date >= :StartingDate 
                    AND PM.Date <= :EndingDate
                    ORDER BY PM.Date, PM.KindId;";
            
            $params = [
                "StartingDate" => $startingDate->format("Y-m-d")
                , "EndingDate" => $endingDate->format("Y-m-d")
            ];

            $this->db->BeginTransaction();

            $rows = $this->db->Read($query, $params);

            $plannifiedMeals = [];

            $mealKindDAL = new MealKindDAL($this->db);
            $mealKinds = $mealKindDAL->Load();

            $mealIds = [];

            foreach ($rows as $row)
            {
                $plannifiedMeal = new PlannifiedMeal();
                $plannifiedMeal->SetId($row["Id"]);
                $plannifiedMeal->SetPersonNumber($row["PersonNumber"]);
                $plannifiedMeal->SetDate(new \DateTime($row["Date"]));
                $plannifiedMeal->SetKind($mealKinds[$row["KindId"]]);

                $mealIds[$plannifiedMeal->GetId()] = $row["MealId"];

                $plannifiedMeals[$plannifiedMeal->GetId()] = $plannifiedMeal;
            }

            if (count($mealIds) > 0)
            {
                $mealDAL = new MealDAL($this->db);
                $meals = $mealDAL->Load($mealIds);

                foreach ($mealIds as $id => $mealId)
                {
                    $plannifiedMeal = $plannifiedMeals[$id];
                    $plannifiedMeal->SetMeal($meals[$mealId]);
                }
            }

            $this->db->Commit();

            return $plannifiedMeals;
        }
        catch (\Exception $e)
        {
            $this->db->Rollback();

            ErrorManager::Manage($e);
        }
    }

    public function Add(array $plannifiedMeals) : void
    {
        try
        {
            $query = "INSERT INTO PlannifiedMeal (Date, PersonNumber, KindId, MealId)
                    VALUES (:Date, :PersonNumber, :KindId, :MealId);";

            $this->db->BeginTransaction();

            foreach ($plannifiedMeals as $plannifiedMeal)
            {
                $params = [];
                $params[":Date"] = $plannifiedMeal->GetDate()->format("Y-m-d");
                $params[":PersonNumber"] = $plannifiedMeal->GetPersonNumber();
                $params[":KindId"] = $plannifiedMeal->GetKind()->GetId();
                $params[":MealId"] = $plannifiedMeal->GetMeal()->GetId();

                $this->db->Execute($query, $params);
            }

            $this->db->Commit();  
        }
        catch (\Exception $e)
        {
            $this->db->Rollback();

            ErrorManager::Manage($e);
        }
    }

    public function Delete(\DateTime $startingDate, \DateTime $endingDate) : void
    {
        try
        {
            $query = "DELETE FROM PlannifiedMeal
                    WHERE Date >= :StartingDate
                    AND Date <= :EndingDate;";

            $params = [];
            $params[":StartingDate"] = $startingDate->format("Y-m-d");
            $params[":EndingDate"] = $endingDate->format("Y-m-d");

            $this->db->BeginTransaction();

            $this->db->Execute($query, $params);

            $this->db->Commit();
        }
        catch (\Exception $e)
        {
            $this->db->Rollback();

            ErrorManager::Manage($e);
        }
    }

    public function Exists(\DateTime $startingDate, \DateTime $endingDate) : bool
    {
        try
        {
            $query = "SELECT COUNT(1) AS Count 
                    FROM PlannifiedMeal
                    WHERE Date >= :StartingDate
                    AND Date <= :EndingDate;";
            
            $params = [];
            $params[":StartingDate"] = $startingDate->format("Y-m-d");
            $params[":EndingDate"] = $endingDate->format("Y-m-d");

            $this->db->BeginTransaction();

            $rows = $this->db->Read($query, $params);

            $this->db->Commit();

            $row = array_pop($rows);

            return $row["Count"] > 0;
        }
        catch (\Exception $e)
        {
            $this->db->Rollback();

            ErrorManager::Manage($e);
        }
    }
}