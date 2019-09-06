<?php

namespace BLL\Recipe;

use DAL\Recipe\RecipeDAL;

class RecipeBLL
{
    private $recipeDAL;

	public function __construct()
	{
		$this->recipeDAL = new RecipeDAL();
    }
    
    public function Load($ids = null)
    {
        return $this->recipeDAL->Load($ids);
    }
}