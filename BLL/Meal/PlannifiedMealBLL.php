<?php

namespace BLL\Meal;

use Framework\DAL\Database;
use Framework\Tools\Error\ErrorManager;
use Model\Meal\PlannifiedMeal;
use Model\Meal\MealKind;
use Model\Meal\MealMealItem;
use Model\Meal\MealItem;
use DAL\Meal\PlannifiedMealDAL;
use DAL\Meal\MealDAL;
use DAL\Meal\MealKindDAL;

class PlannifiedMealBLL
{   
    public function Load(\DateTime $startingDate, \DateTime $endingDate) : array
    {
        try
        {
            $db = new Database();
            $db->BeginTransaction();

            $plannifiedMealDAL = new PlannifiedMealDAL($db);
            $plannifiedMeals = $plannifiedMealDAL->Load($startingDate, $endingDate);

            $db->Commit();

            return $plannifiedMeals;
        }
        catch (\Exception $e)
        {
            if ($db != null)
                $db->Rollback();

            ErrorManager::Manage($e);
        }
    }

    public function Add(array $plannifiedMeals) : void
    {
        try
        {
            $db = new Database();
            $db->BeginTransaction();

            $plannifiedMealDAL = new PlannifiedMealDAL($db);
            $plannifiedMealDAL->Add($plannifiedMeals);

            $db->Commit();
        }
        catch (\Exception $e)
        {
            if ($db != null)
                $db->Rollback();

            ErrorManager::Manage($e);
        }
    }

    public function Delete(\DateTime $startingDate, \DateTime $endingDate) : void
    {
        try
        {
            $db = new Database();
            $db->BeginTransaction();

            $plannifiedMealDAL = new PlannifiedMealDAL($db);
            $plannifiedMealDAL->Delete($startingDate, $endingDate);

            $db->Commit();
        }
        catch (\Exception $e)
        {
            if ($db != null)
                $db->Rollback();

            ErrorManager::Manage($e);
        }
    }

    public function Exists(\DateTime $startingDate, \DateTime $endingDate) : bool
    {
        try
        {
            $db = new Database();
            $db->BeginTransaction();

            $plannifiedMealDAL = new PlannifiedMealDAL($db);
            $exists = $plannifiedMealDAL->Exists($startingDate, $endingDate);

            $db->Commit();

            return $exists;
        }
        catch (\Exception $e)
        {
            if ($db != null)
                $db->Rollback();

            ErrorManager::Manage($e);
        }
    }

    public function Generate(\DateTime $startingDate, \DateTime $endingDate, int $personNumber) : array
    {
        try
        {
            $db = new Database();
            $db->BeginTransaction();

            // Chargement de l'ensemble des repas connus.
            $mealDAL = new MealDAL($db);
            $meals = $mealDAL->Load();

            $mealIds = array_keys($meals);
            $minMealId = min($mealIds);
            $maxMealId = max($mealIds);

            // Chargement des types de repas.
            $mealKindDAL = new MealKindDAL($db);
            $mealKinds = $mealKindDAL->Load();

            $db->Commit();

            // Détermination du nombre de jours d'écart.
            $numberOfDays = $endingDate->diff($startingDate)->format("%a") + 1;

            $plannifiedMeals = [];
            $plannifiedMealIds = [];
            $plannifiedMealItemCount = [];

            for ($i = 0; $i < $numberOfDays; $i++)
            {
                // Construction de la date en cours de traitement.
                $currentDate = new \DateTime($startingDate->format("Y-m-d"));
                $currentDate->modify("+" . $i . " day");

                // Génération du déjeuner.
                $plannifiedMeal = new PlannifiedMeal();
                $plannifiedMeal->SetDate($currentDate);
                $plannifiedMeal->SetPersonNumber($personNumber);
                $mealKind = $mealKinds[MealKInd::LUNCH_CODE];
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
                                $isItemsOk = true;
                                foreach ($meal->GetItems() as $item)
                                {
                                    if (array_key_exists($item->GetMealItem()->GetId(), $plannifiedMealItemCount) 
                                    && $plannifiedMealItemCount[$item->GetMealItem()->GetId()] >= $item->GetMealItem()->GetWeekProposedMaxCount())
                                    {
                                        $isItemsOk = false;
                                        break;
                                    }
                                }

                                // Si les repas précédents contiennent déjà un des composants.
                                if (!$isItemsOk)
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
                foreach ($meal->GetItems() as $item)
                {
                    if (!array_key_exists($item->GetMealItem()->GetId(), $plannifiedMealItemCount))
                        $plannifiedMealItemCount[$item->GetMealItem()->GetId()] = 0;

                    $plannifiedMealItemCount[$item->GetMealItem()->GetId()]++;
                }

                // Génération du dîner.
                $plannifiedMeal = new PlannifiedMeal();
                $plannifiedMeal->SetDate($currentDate);
                $plannifiedMeal->SetPersonNumber($personNumber);
                $mealKind = $mealKinds[MealKind::DINNER_CODE];
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
                                $isItemsOk = true;
                                foreach ($meal->GetItems() as $item)
                                {
                                    if (array_key_exists($item->GetMealItem()->GetId(), $plannifiedMealItemCount) 
                                    && $plannifiedMealItemCount[$item->GetMealItem()->GetId()] >= $item->GetMealItem()->GetWeekProposedMaxCount())
                                    {
                                        $isItemsOk = false;
                                        break;
                                    }
                                }

                                // Si les repas précédents contiennent déjà un des composants.
                                if (!$isItemsOk)
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
                foreach ($meal->GetItems() as $item)
                {
                    if (!array_key_exists($item->GetMealItem()->GetId(), $plannifiedMealItemCount))
                        $plannifiedMealItemCount[$item->GetMealItem()->GetId()] = 0;

                    $plannifiedMealItemCount[$item->GetMealItem()->GetId()]++;
                }
            }

            return $plannifiedMeals;
        }
        catch (\Exception $e)
        {
            if ($db != null)
                $db->Rollback();

            ErrorManager::Manage($e);
        }
    }
}