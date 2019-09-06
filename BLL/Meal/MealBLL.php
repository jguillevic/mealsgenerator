<?php

namespace BLL\Meal;

use \DAL\Meal\MealDAL;

class MealBLL
{
    private $mealDAL;

	public function __construct()
	{
		$this->mealDAL = new MealDAL();
    }
    
    public function Load()
    {
        return $this->mealDAL->Load();
    }
}