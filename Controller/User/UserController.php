<?php

namespace Controller\User;

use Framework\View\View;
use Framework\Tools\Helper\RoutesHelper;
use Framework\Tools\Helper\PathHelper;

class UserController
{
    public function Login($queryParameters)
    {
        $path = PathHelper::GetPath([ "User", "Login" ]);
        $view = new View($path);

        return $view->Render();
    }
}