<?php

namespace DAL\Unit;

use Framework\DAL\Database;
use Framework\Tools\Error\ErrorManager;
use Model\Unit\UnitCategory;

class UnitCategoryDAL
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
        try
        {
            $query = "SELECT UC.Id, UC.Code FROM UnitCategory AS UC;";

            $rows = $this->db->Read($query);

            $unitCategories = [];

            foreach ($rows as $row)
            {
                $unitCategory = new UnitCategory();

                $unitCategory->SetId($row["Id"]);
                $unitCategory->SetCode($row["Code"]);

                $unitCategories[$unitCategory->GetId()] = $unitCategory;
            }

            return $unitCategories;
        }
        catch (\Exception $e)
        {
            ErrorManager::Manage($e);
        }
    }
}