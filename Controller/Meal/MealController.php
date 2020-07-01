<?php

namespace Controller\Meal;

use \Framework\View\View;
use \Tools\Helper\UserHelper;
use \Model\Meal\Meal;
use \BLL\Meal\MealBLL;
use \Framework\Tools\Helper\RoutesHelper;
use \Framework\Tools\Helper\PathHelper;

class MealController
{
    public function Display($queryParameters)
	{
        try
        {
            if (UserHelper::IsLogin())
            {
                $currentDayOfWeekInInt = strftime("%u");

                $startingDateTime = new \DateTime("NOW");
                $value = $currentDayOfWeekInInt - 1;
                $startingDateTime->sub(new \DateInterval("P".$value."D"));

                $endingDateTime = new \DateTime("NOW");
                $value = 7 - $currentDayOfWeekInInt;
                $endingDateTime->add(new \DateInterval("P".$value."D"));

                $mealBLL = new MealBLL();
                $meals = $mealBLL->Load($startingDateTime, $endingDateTime);

                $path = PathHelper::GetPath([ "Meal", "Display" ]);   
                $view = new View($path);
                
                return $view->Render([ "meals" => $meals ]);
            }

            RoutesHelper::Redirect("UserLogin");
        }
        catch (\Exception $e)
        {
            ErrorManager::Manage($e);
        }
    }

    public function Add($queryParameters)
	{
    }

    public function Update($queryParameters)
	{
    }

    public function Delete($queryParameters)
	{
    }
}