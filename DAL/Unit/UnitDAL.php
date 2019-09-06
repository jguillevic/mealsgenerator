<?php

namespace DAL\Unit;

use Framework\DAL\Database;
use Model\Unit\Unit;
use DAL\Unit\UnitCategoryDAL;

class UnitDAL
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
        $query = "SELECT U.Id
                  , U.Name
                  , U.Code
                  , U.ConversionFactor
                  , U.CategoryId 
                  FROM Unit AS U;";
        
        $rows = $this->db->Read($query);

        $unitCategoryDAL = new UnitCategoryDAL($this->db);
        $unitCategories = $unitCategoryDAL->LOad();

        $units = [];

        foreach ($rows as $row)
        {
            $unit = new Unit();

            $unitId = $row["Id"];

            $unit->SetId($unitId);
            $unit->SetName($row["Name"]);
            $unit->SetCode($row["Code"]);
            $unit->SetConversionFactor($row["ConversionFactor"]);
            $unit->SetCategory($unitCategories[$row["CategoryId"]]);

            $units[$unitId] = $unit;
        }

        return $units;
    }
}