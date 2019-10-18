<?php

namespace DAL\Meal;

use Framework\DAL\Database;
use Framework\Tools\Error\ErrorManager;
use Model\Meal\MealKind;

class MealKindDAL
{
    private $db;
    
	public function __construct(Database $db = null)
	{
		if (isset($db))
			$this->db = $db;
		else
			$this->db = new Database();
    }

    public function Load() : array
    {
        try
        {
            $query = "SELECT MK.Id, MK.Code, MK.Name FROM MealKind AS MK;";

            $this->db->BeginTransaction();

            $rows = $this->db->Read($query);

            $this->db->Commit();
        
            $mealKinds = [];

            foreach ($rows as $row)
            {
                $mealKind = new MealKind();
                $mealKind->SetId($row["Id"]);
                $mealKind->SetCode($row["Code"]);
                $mealKind->SetName($row["Name"]);

                $mealKinds[$mealKind->GetId()] = $mealKind;
                $mealKinds[$mealKind->GetCode()] = $mealKind;
            }

            return $mealKinds;
        }
        catch (\Exception $e)
        {
            $this->db->Rollback();

            ErrorManager::Manage($e);
        }
    }
}