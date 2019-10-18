<?php

namespace Controller\Recipe;

use Framework\View\View;
use Framework\Tools\Helper\RoutesHelper;
use Framework\Tools\Helper\PathHelper;
use BLL\Recipe\RecipeBLL;
use Tools\Helper\UserHelper;

class RecipeController
{
    public function Display($queryParameters)
    {
        try
        {
            if (UserHelper::IsLogin())
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
            else
                RoutesHelper::Redirect("UserLogin");
        }
        catch (\Exception $e)
        {
            ErrorManager::Manage($e);
        }
    }
}