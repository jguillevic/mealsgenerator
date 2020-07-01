<?php

namespace BLL\ShoppingList;

use Framework\DAL\Database;
use Framework\Tools\Error\ErrorManager;
use DAL\ShoppingList\ShoppingListDAL;
use DAL\Meal\PlannifiedMealDAL;
use DAL\Unit\UnitDAL;
use Model\ShoppingList\ShoppingList;
use Model\ShoppingList\ShoppingListItem;

class ShoppingListBLL
{
    public function Load() : array
    {
        try
        {
            $db = new Database();
            $db->BeginTransaction();

            $shoppingListDAL = new ShoppingListDAL($db);
            $shoppingLists = $shoppingListDAL->Load();

            $db->Commit();

            return $shoppingLists;
        }
        catch (\Exception $e)
        {
            if ($db != null)
                $db->Rollback();

            ErrorManager::Manage($e);
        }
    }

    public function Generate(\DateTime $startingDate, \DateTime $endingDate) : ShoppingList
    {
        try
        {
            $db = new Database();
            $db->BeginTransaction();

            $plannifiedMealDAL = new PlannifiedMealDAL($db);
            $plannifiedMeals = $plannifiedMealDAL->Load($startingDate, $endingDate);

            $unitDAL = new UnitDAL($db);
            $units = $unitDAL->Load();

            $mealItemsCount = [];
            $ingredientsQuantity = [];

            foreach ($plannifiedMeals as $plannifiedMeal)
            {
                foreach ($plannifiedMeal->GetMeal()->GetItems() as $mealItem)
                {   
                    $recipe = $mealItem->GetMealItem()->GetRecipe();

                    if ($recipe == null)
                    {
                        $mealItemId = $mealItem->GetMealItem()->GetId();

                        if (!array_key_exists($mealItemId, $mealItemsCount))
                            $mealItemsCount[$mealItemId] = [ "MealItem" => $mealItem->GetMealItem(), "Count" => 0 ];
                    
                        $mealItemsCount[$mealItemId]["Count"] += $plannifiedMeal->GetPersonNumber();
                    }
                    // Dans le cas où il y a une recette associée, on récupère les ingrédients.
                    else
                    {
                        foreach ($recipe->GetIngredients() as $ingredient)
                        {
                            $ingredientId = $ingredient->GetIngredient()->GetId();

                            if (!array_key_exists($ingredientId, $ingredientsQuantity))
                                $ingredientsQuantity[$ingredientId] = [ "Ingredient" => $ingredient->GetIngredient(), "Quantity" => 0, "Unit" => $ingredient->GetIngredient()->GetDefaultUnit() ];

                            if ($ingredient->GetUnit()->GetConversionFactor() != null && $ingredient->GetIngredient()->GetDefaultUnit()->GetConversionFactor() != null)
                            {
                                $convertedQuantity = $ingredient->GetQuantity() 
                                * $ingredient->GetUnit()->GetConversionFactor()
                                / $ingredient->GetIngredient()->GetDefaultUnit()->GetConversionFactor();
                            }
                            else
                            {
                                $convertedQuantity = $ingredient->GetQuantity();
                            }

                            $ingredientsQuantity[$ingredientId]["Quantity"] += 
                                $plannifiedMeal->GetPersonNumber() / $recipe->GetDefaultPersonNumber() 
                                * $convertedQuantity;
                        }
                    }
                }
            }

            $shoppingList = new ShoppingList();

            $name = "Liste des courses du " . $startingDate->format("d/m/Y") . " au " . $endingDate->format("d/m/Y");
            $shoppingList->SetName($name);

            foreach ($mealItemsCount as $mealItemCount)
            {
                $shoppingListItem = new ShoppingListItem();

                $shoppingListItem->SetContent($mealItemCount["Count"] . " portions(s) de " . $mealItemCount["MealItem"]->GetName());

                $shoppingList->AddItems([ $shoppingListItem ]);
            }

            foreach ($ingredientsQuantity as $ingredientQuantity)
            {
                $shoppingListItem = new ShoppingListItem();

                $content = $ingredientQuantity["Quantity"] . " ";
                if (!empty($ingredientQuantity["Unit"]))
                    $content .= $ingredientQuantity["Unit"]->GetCode() . " de " . $ingredientQuantity["Ingredient"]->GetName();
                else
                    $content .= $ingredientQuantity["Ingredient"]->GetName();

                $shoppingListItem->SetContent($content);

                $shoppingList->AddItems([ $shoppingListItem ]);
            }

            $shoppingListDAL = new ShoppingListDAL($db);
            $shoppingListDAL->Delete();
            $shoppingListDAL->Add([ $shoppingList ]);

            $db->Commit();
            
            return $shoppingList;
        }
        catch (\Exception $e)
        {
            if ($db != null)
                $db->Rollback();

            ErrorManager::Manage($e);
        }
    }
}