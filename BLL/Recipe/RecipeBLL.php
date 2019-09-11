<?php

namespace BLL\Recipe;

use Framework\DAL\Database;
use Framework\Tools\Error\ErrorManager;
use DAL\Recipe\RecipeDAL;

class RecipeBLL
{   
    public function Load($ids = null)
    {
        try
        {
            $db = new Database();
            $db->BeginTransaction();

            $recipeDAL = new RecipeDAL($db);
            $recipes = $recipeDAL->Load($ids);

            $db->Commit();

            return $recipes;
        }
        catch (\Exception $e)
        {
            if ($db != null)
                $db->Rollback();

            ErrorManager::Manage($e);
        }
    }
}