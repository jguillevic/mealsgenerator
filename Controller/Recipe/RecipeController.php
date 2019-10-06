<?php

namespace Controller\Recipe;

use Framework\View\View;
use Framework\Tools\Helper\RoutesHelper;
use Framework\Tools\Helper\PathHelper;
use BLL\Recipe\RecipeBLL;

class RecipeController
{
    public function Display($queryParameters)
    {
        if ($_SERVER["REQUEST_METHOD"] == "GET")
        {
            $id = $queryParameters["Id"]->GetValue();

            $recipeBLL = new RecipeBLL();
            $recipes = $recipeBLL->Load([ $id ]);

            $path = PathHelper::GetPath([ "Recipe", "Display" ]);
            $view = new View($path);

            return $view->Render([ "Recipe" => array_pop($recipes) ]);
        }

        RoutesHelper::Redirect("DisplayError");
    }
}