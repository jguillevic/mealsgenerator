<?php

namespace DAL\Meal;

use Framework\DAL\Database;
use Model\Meal\MealKind;

class MealKindDAL
{
    private $db;
    
	public function __construct($db = null)
	{
		if (isset($db))
			$this->db = $db;
		else
			$this->db = new Database();
    }

    public function Load()
    {
        $query = "SELECT MK.Id, MK.Code, MK.Name FROM MealKind AS MK;";

        $rows = $this->db->Read($query);

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
}