<?php

namespace DAL\Unit;

use Framework\DAL\Database;
use Framework\Tools\Error\ErrorManager;
use Model\Unit\UnitCategory;

class UnitCategoryDAL
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
            $query = "SELECT UC.Id, UC.Code FROM UnitCategory AS UC;";

            $this->db->BeginTransaction();

            $rows = $this->db->Read($query);

            $this->db->Commit();

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
            $this->db->Rollback();

            ErrorManager::Manage($e);
        }
    }
}