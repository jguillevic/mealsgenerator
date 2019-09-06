<?php

namespace BLL\Meal;

use Model\Meal\PlannifiedMeal;
use Model\Meal\MealKind;
use Model\Meal\MealMealPart;
use Model\Meal\MealPart;
use DAL\Meal\PlannifiedMealDAL;
use DAL\Meal\MealDAL;
use DAL\Meal\MealKindDAL;

class PlannifiedMealBLL
{
    // Période en jours de non répétition des repas.
    const NoMealRepeatPeriod = 7;

    private $plannifiedMealDAL;

	public function __construct()
	{
		$this->plannifiedMealDAL = new PlannifiedMealDAL();
    }
    
    public function Load($startingDate, $endingDate)
    {
        return $this->plannifiedMealDAL->Load($startingDate, $endingDate);
    }

    public function Add($plannifiedMeals)
    {
        $this->plannifiedMealDAL->Add($plannifiedMeals);
    }

    public function Delete($startingDate, $endingDate)
    {
        $this->plannifiedMealDAL->Delete($startingDate, $endingDate);
    }

    public function Exists($startingDate, $endingDate)
    {
        return $this->plannifiedMealDAL->Exists($startingDate, $endingDate);
    }

    public function Generate($startingDate, $endingDate, $personNumber)
    {
        // Chargement de l'ensemble des repas connus.
        $mealDAL = new MealDAL();
        $meals = $mealDAL->Load();

        $mealIds = array_keys($meals);
        $minMealId = min($mealIds);
        $maxMealId = max($mealIds);

        // Chargement des types de repas.
        $mealKindDAL = new MealKindDAL();
        $mealKinds = $mealKindDAL->Load();

        // Détermination du nombre de jours d'écart.
        $numberOfDays = $endingDate->diff($startingDate)->format("%a") + 1;

        $plannifiedMeals = [];
        $plannifiedMealIds = [];
        $plannifiedMealPartCount = [];

        for ($i = 0; $i < $numberOfDays; $i++)
        {
            // Construction de la date en cours de traitement.
            $currentDate = new \DateTime($startingDate->format("Y-m-d"));
            $currentDate->modify("+" . $i . " day");

            // Génération du déjeuner.
            $plannifiedMeal = new PlannifiedMeal();
            $plannifiedMeal->SetDate($currentDate);
            $plannifiedMeal->SetPersonNumber($personNumber);
            $mealKind = $mealKinds["LUNCH"];
            $plannifiedMeal->SetKind($mealKind);

            $mealFound = false;
            while (!$mealFound)
            {
                $mealId = rand($minMealId, $maxMealId);

                // Si l'identifiant de repas n'existe pas.
                if (!array_key_exists($mealId, $meals))
                    $mealFound = false;
                else 
                {
                    $meal = $meals[$mealId];

                    // Si le repas n'est pas compatible avec le type de repas en cours de génération. 
                    if (!array_key_exists($plannifiedMeal->GetKind()->GetId(), $meal->GetPotentialKinds()))
                    {
                        $mealFound = false;
                    }
                    else 
                    {
                        // Si le repas n'a pas déjà été plannifié.
                        if (in_array($mealId, $plannifiedMealIds))
                            $mealFound = false;
                        else
                        {
                            $isPartsOk = true;
                            foreach ($meal->GetParts() as $part)
                            {
                                if (array_key_exists($part->GetMealPart()->GetId(), $plannifiedMealPartCount) 
                                && $plannifiedMealPartCount[$part->GetMealPart()->GetId()] >= $part->GetMealPart()->GetWeekProposedMaxCount())
                                {
                                    $isPartsOk = false;
                                    break;
                                }
                            }

                            // Si les repas précédents contiennent déjà un des composants.
                            if (!$isPartsOk)
                                $mealFound = false;
                            else
                                $mealFound = true;
                        }
                    }
                }
            }

            $plannifiedMeal->SetMeal($meals[$mealId]);

            $plannifiedMeals[] = $plannifiedMeal;
            $meal = $plannifiedMeal->GetMeal();
            $plannifiedMealIds[] = $meal->GetId();
            foreach ($meal->GetParts() as $part)
            {
                if (!array_key_exists($part->GetMealPart()->GetId(), $plannifiedMealPartCount))
                    $plannifiedMealPartCount[$part->GetMealPart()->GetId()] = 0;

                $plannifiedMealPartCount[$part->GetMealPart()->GetId()]++;
            }

            // Génération du dîner.
            $plannifiedMeal = new PlannifiedMeal();
            $plannifiedMeal->SetDate($currentDate);
            $plannifiedMeal->SetPersonNumber($personNumber);
            $mealKind = $mealKinds["DINNER"];
            $plannifiedMeal->SetKind($mealKind);

            $mealFound = false;
            while (!$mealFound)
            {
                $mealId = rand($minMealId, $maxMealId);

                // Si l'identifiant de repas n'existe pas.
                if (!array_key_exists($mealId, $meals))
                    $mealFound = false;
                else 
                {
                    $meal = $meals[$mealId];

                    // Si le repas n'est pas compatible avec le type de repas en cours de génération. 
                    if (!array_key_exists($plannifiedMeal->GetKind()->GetId(), $meal->GetPotentialKinds()))
                    {
                        $mealFound = false;
                    }
                    else 
                    {
                        // Si le repas n'a pas déjà été plannifié.
                        if (in_array($mealId, $plannifiedMealIds))
                            $mealFound = false;
                        else
                        {
                            $isPartsOk = true;
                            foreach ($meal->GetParts() as $part)
                            {
                                if (array_key_exists($part->GetMealPart()->GetId(), $plannifiedMealPartCount) 
                                && $plannifiedMealPartCount[$part->GetMealPart()->GetId()] >= $part->GetMealPart()->GetWeekProposedMaxCount())
                                {
                                    $isPartsOk = false;
                                    break;
                                }
                            }

                            // Si les repas précédents contiennent déjà un des composants.
                            if (!$isPartsOk)
                                $mealFound = false;
                            else
                                $mealFound = true;
                        }
                    }
                }
            }

            $plannifiedMeal->SetMeal($meals[$mealId]);

            $plannifiedMeals[] = $plannifiedMeal;
            $meal = $plannifiedMeal->GetMeal();
            $plannifiedMealIds[] = $meal->GetId();
            foreach ($meal->GetParts() as $part)
            {
                if (!array_key_exists($part->GetMealPart()->GetId(), $plannifiedMealPartCount))
                    $plannifiedMealPartCount[$part->GetMealPart()->GetId()] = 0;

                $plannifiedMealPartCount[$part->GetMealPart()->GetId()]++;
            }
        }

        return $plannifiedMeals;
    }
}