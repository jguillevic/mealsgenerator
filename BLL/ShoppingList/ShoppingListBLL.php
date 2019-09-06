<?php

namespace BLL\ShoppingList;

use DAL\ShoppingList\ShoppingListDAL;
use DAL\Meal\PlannifiedMealDAL;
use DAL\Unit\UnitDAL;
use Model\ShoppingList\ShoppingList;
use Model\ShoppingList\ShoppingListItem;

class ShoppingListBLL
{
    private $shoppingListDAL;

	public function __construct()
	{
		$this->shoppingListDAL = new ShoppingListDAL();
    }

    public function Load()
    {
        return $this->shoppingListDAL->Load();
    }

    public function Generate($startingDate, $endingDate)
    {
        $plannifiedMealDAL = new PlannifiedMealDAL();
        $plannifiedMeals = $plannifiedMealDAL->Load($startingDate, $endingDate);

        $unitDAL = new UnitDAL();
        $units = $unitDAL->Load();

        $mealPartsCount = [];
        $ingredientsQuantity = [];

        foreach ($plannifiedMeals as $plannifiedMeal)
        {
            foreach ($plannifiedMeal->GetMeal()->GetParts() as $mealPart)
            {   
                $recipe = $mealPart->GetMealPart()->GetRecipe();

                if ($recipe == null)
                {
                    $mealPartId = $mealPart->GetMealPart()->GetId();

                    if (!array_key_exists($mealPartId, $mealPartsCount))
                        $mealPartsCount[$mealPartId] = [ "MealPart" => $mealPart->GetMealPart(), "Count" => 0 ];
                
                    $mealPartsCount[$mealPartId]["Count"] += $plannifiedMeal->GetPersonNumber();
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

        foreach ($mealPartsCount as $mealPartCount)
        {
            $shoppingListItem = new ShoppingListItem();

            $shoppingListItem->SetContent($mealPartCount["Count"] . " part(s) de " . $mealPartCount["MealPart"]->GetName());

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

        $shoppingListDAL = new ShoppingListDAL();
        $shoppingListDAL->Delete();
        $shoppingListDAL->Add([ $shoppingList ]);

        return $shoppingList;
    }
}