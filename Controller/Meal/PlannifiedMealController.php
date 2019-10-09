<?php

namespace Controller\Meal;

use Framework\View\View;
use Framework\Tools\Helper\RoutesHelper;
use Framework\Tools\Helper\PathHelper;
use BLL\Meal\PlannifiedMealBLL;
use Model\Meal\PlannifiedMeal;
use Tools\Helper\UserHelper;

class PlannifiedMealController
{
    public function Display($queryParameters)
    {
        if (UserHelper::IsLogin())
        {
            if ($_SERVER["REQUEST_METHOD"] == "GET")
            {
                if (!array_key_exists("StartingDate", $queryParameters)
                || !array_key_exists("EndingDate", $queryParameters))
                {
                    $startingDate = new \DateTime();
                    $endingDate = (new \DateTime())->modify("+6 day");
                }
                else
                {
                    $startingDate = new \DateTime($queryParameters["StartingDate"]->GetValue());
                    $endingDate = new \DateTime($queryParameters["EndingDate"]->GetValue());
                }

                $plannifiedMealBLL = new PlannifiedMealBLL();
                $plannifiedMeals = $plannifiedMealBLL->Load($startingDate, $endingDate);

                $plannifiedMealsByDate = [];

                foreach ($plannifiedMeals as $plannifiedMeal)
                {
                    $date = $plannifiedMeal->GetDate()->format("Y-m-d");

                    if (!array_key_exists($date, $plannifiedMealsByDate))
                        $plannifiedMealsByDate[$date] = [];

                    $plannifiedMealsByDate[$date]["Date"] = $plannifiedMeal->GetDate();
                    $plannifiedMealsByDate[$date][$plannifiedMeal->GetKind()->GetCode()] = $plannifiedMeal;
                }

                $path = PathHelper::GetPath([ "Meal", "Display" ]);
                $view = new View($path);

                return $view->Render(
                    [ 
                        "PlannifiedMeals" => $plannifiedMealsByDate
                        , "StartingDate" => $startingDate
                        , "EndingDate" => $endingDate
                    ]);
            }
            else
                RoutesHelper::Redirect("DisplayError");
        }
        else
            RoutesHelper::Redirect("UserLogin");
    }

    public function Add($queryParameters)
    {
        if (UserHelper::IsLogin())
        {
            if ($_SERVER["REQUEST_METHOD"] == "POST")
            {
                $jsonPlannifiedMeals = $queryParameters["plannifiedMeals"]->GetValue();
                $stdClassPlannifiedMeals = json_decode($jsonPlannifiedMeals);

                $plannifiedMeals = [];
                $dates = [];

                foreach ($stdClassPlannifiedMeals as $stdClassPlannifiedMeal)
                {
                    $plannifiedMeal = (new PlannifiedMeal())->SetFromStdClass($stdClassPlannifiedMeal);
                    $plannifiedMeals[] = $plannifiedMeal;

                    if (!in_array($plannifiedMeal->GetDate(), $dates))
                        $dates[] = $plannifiedMeal->GetDate();
                }

                $plannifiedMealBLL = new PlannifiedMealBLL();

                $plannifiedMealBLL->Delete(min($dates), max($dates));

                $plannifiedMealBLL->Add($plannifiedMeals);

                RoutesHelper::Redirect("DisplayHome");
            }
            else
                RoutesHelper::Redirect("DisplayError");
        }
        else
            RoutesHelper::Redirect("UserLogin");
    }

    public function Generate($queryParameters)
    {
        if (UserHelper::IsLogin())
        {
            if ($_SERVER["REQUEST_METHOD"] == "GET")
            {
                if (!array_key_exists("StartingDate", $queryParameters)
                    || !array_key_exists("EndingDate", $queryParameters))
                {
                    $startingDate = new \DateTime();
                    $endingDate = (new \DateTime())->modify("+6 day");
                }
                else
                {
                    $startingDate = new \DateTime($queryParameters["StartingDate"]->GetValue());
                    $endingDate = new \DateTime($queryParameters["EndingDate"]->GetValue());
                }

                if (!array_key_exists("PersonNumber", $queryParameters))
                    $personNumber = 2;
                else
                    $personNumber = $queryParameters["PersonNumber"]->GetValue();

                $plannifiedMealBLL = new PlannifiedMealBLL();
                $plannifiedMeals = $plannifiedMealBLL->Generate($startingDate, $endingDate, $personNumber);

                $plannifiedMealsByDate = [];

                foreach ($plannifiedMeals as $plannifiedMeal)
                {
                    $date = $plannifiedMeal->GetDate()->format("Y-m-d");

                    if (!array_key_exists($date, $plannifiedMealsByDate))
                        $plannifiedMealsByDate[$date] = [];

                    $plannifiedMealsByDate[$date]["Date"] = $plannifiedMeal->GetDate();
                    $plannifiedMealsByDate[$date][$plannifiedMeal->GetKind()->GetCode()] = $plannifiedMeal;
                }

                $isExisting = $plannifiedMealBLL->Exists($startingDate, $endingDate);

                $path = PathHelper::GetPath([ "Meal", "GeneratePlannifiedMeals" ]);
                $view = new View($path);

                return $view->Render(
                    [ 
                        "PlannifiedMealsByDate" => $plannifiedMealsByDate
                        , "PlannifiedMeals" => $plannifiedMeals
                        , "StartingDate" => $startingDate
                        , "EndingDate" => $endingDate
                        , "PersonNumber" => $personNumber 
                        , "IsExisting" => $isExisting
                    ]);
            }
            else
                RoutesHelper::Redirect("DisplayError");
        }
        else
            RoutesHelper::Redirect("UserLogin");
    }
}