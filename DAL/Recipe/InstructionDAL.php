<?php

namespace DAL\Recipe;

use Framework\DAL\Database;
use Framework\DAL\DALHelper;
use Framework\Tools\Error\ErrorManager;
use Model\Recipe\Instruction;

class InstructionDAL
{
    private $db;
    
	public function __construct($db = null)
	{
		if (isset($db))
			$this->db = $db;
		else
			$this->db = new Database();
    }

    public function Load($recipeIds)
    {
        try
        {
            $query = "SELECT I.Id, I.Order, I.Content, I.RecipeId FROM Instruction AS I WHERE ";

            $params = [];
            $query .= DALHelper::SetArrayParams($recipeIds, "I", "RecipeId", $params);

            $query .= " ORDER BY I.RecipeId, I.Order;";

            $this->db->BeginTransaction();

            $rows = $this->db->Read($query, $params);

            $this->db->Commit();

            $instructions = [];

            foreach ($rows as $row)
            {
                $instruction = new Instruction();
                $instruction->SetId($row["Id"]);
                $instruction->SetOrder($row["Order"]);
                $instruction->SetContent($row["Content"]);

                $recipeId = $row["RecipeId"];

                if (!array_key_exists($recipeId, $instructions))
                    $instructions[$recipeId] = [];
                
                $instructions[$recipeId][$instruction->GetId()] = $instruction;
            }

            return $instructions;
        }
        catch (\Exception $e)
        {
            $this->db->Rollback();

            ErrorManager::Manage($e);
        }
    }
}