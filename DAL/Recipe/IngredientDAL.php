<?php

namespace DAL\Recipe;

use Framework\DAL\Database;
use Framework\DAL\DALHelper;
use Framework\Tools\Error\ErrorManager;
use Model\Recipe\Ingredient;
use DAL\Unit\UnitDAL;

class IngredientDAL
{
    private $db;
    
	public function __construct(Database $db = null)
	{
		if (isset($db))
			$this->db = $db;
		else
			$this->db = new Database();
    }

    public function Load(array $ingredientIds) : array
    {
        try
        {
            $query = "SELECT I.Id
                    , I.Name
                    , I.DefaultUnitId
                    FROM Ingredient AS I
                    WHERE ";

            $params = [];
            $query .= DALHelper::SetArrayParams($ingredientIds, "I", "Id", $params);

            $query .= ";";

            $this->db->BeginTransaction();

            $rows = $this->db->Read($query, $params);

            $unitDAL = new UnitDAL($this->db);
            $units = $unitDAL->Load();

            $this->db->Commit();

            $ingredients = [];

            foreach ($rows as $row)
            {
                $ingredient = new Ingredient();
                
                $ingredient->SetId($row["Id"]);
                $ingredient->SetName($row["Name"]);
                $ingredient->SetDefaultUnit($units[$row["DefaultUnitId"]]);

                $ingredients[$ingredient->GetId()] = $ingredient;
            }

            return $ingredients;
        }
        catch (\Exception $e)
        {
            $this->db->Rollback();

            ErrorManager::Manage($e);
        }
    }
}