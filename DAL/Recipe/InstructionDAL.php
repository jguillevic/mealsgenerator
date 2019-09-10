<?php

namespace DAL\Recipe;

use Framework\DAL\Database;
use Framework\DAL\DALHelper;
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
        $query = "SELECT I.Id, I.Order, I.Content, I.RecipeId FROM Instruction AS I WHERE ";

        $params = [];
        $query .= DALHelper::SetArrayParams($recipeIds, "I", "RecipeId", $params);

        $query .= " ORDER BY I.RecipeId, I.Order;";

        $rows = $this->db->Read($query, $params);

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
}