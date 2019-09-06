<?php

namespace Controller\ShoppingList;

use Framework\View\View;
use Framework\Tools\Helper\RoutesHelper;
use Framework\Tools\Helper\PathHelper;
use BLL\ShoppingList\ShoppingListBLL;

class ShoppingListController
{
    public function Display($queryParameters)
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

            $shoppingListBLL = new ShoppingListBLL();
            $shoppingLists = $shoppingListBLL->Load();

            $path = PathHelper::GetPath([ "ShoppingList", "DisplayShoppingLists" ]);
            $view = new View($path);

            return $view->Render(
                [ 
                    "ShoppingLists" => $shoppingLists
                    , "StartingDate" => $startingDate
                    , "EndingDate" => $endingDate 
                ]);
        }

        RoutesHelper::Redirect("DisplayError");
    }

    public function Generate($queryParameters)
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

            $shoppingListBLL = new ShoppingListBLL();
            $shoppingList = $shoppingListBLL->Generate($startingDate, $endingDate);

            return RoutesHelper::Redirect("DisplayShoppingLists", 
            [ 
                "ShoppingLists" => [ $shoppingList ]
                , "StartingDate" => $startingDate->format("Y-m-d")
                , "EndingDate" => $endingDate->format("Y-m-d")
            ]);
        }

        RoutesHelper::Redirect("DisplayError");
    }
}