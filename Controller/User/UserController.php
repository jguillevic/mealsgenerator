<?php

namespace Controller\User;

use Framework\View\View;
use Framework\Tools\Helper\RoutesHelper;
use Framework\Tools\Helper\PathHelper;
use BLL\User\UserBLL;
use Model\User\User;
use Tools\Helper\UserHelper;

class UserController
{
    private $salt = "191190+260886+Simba+Jazz";

    public function Login($queryParameters)
    {
        try
        {
            if (UserHelper::IsLogin())
                RoutesHelper::Redirect("DisplayHome");

            $path = PathHelper::GetPath([ "User", "Login" ]);
            $view = new View($path);

            $user = new User();

            if ($_SERVER["REQUEST_METHOD"] == "POST")
            {
                $login = $queryParameters["login"]->GetValue();
                $user->SetLogin($login);
                $password = $queryParameters["password"]->GetValue();
                $passwordHash = hash("SHA512", $this->salt . $password);

                $userBLL = new UserBLL();

                $isLoginExists = $userBLL->IsLoginExists($login);

                if ($isLoginExists)
                {
                    $isPasswordHashMatches = $userBLL->IsPasswordHashMatches($login, $passwordHash);

                    if ($isPasswordHashMatches)
                    {
                        $user = $userBLL->LoadFromLogin($login);
                        UserHelper::Login($user);
                        RoutesHelper::Redirect("DisplayHome");
                    }
                }
            }

            return $view->Render([ "User" => $user ]);
        }
        catch (\Exception $e)
        {
            ErrorManager::Manage($e);
        }
    }

    public function Register($queryParameters)
    {
        try
        {
            if (UserHelper::IsLogin())
                RoutesHelper::Redirect("DisplayHome");

            $path = PathHelper::GetPath([ "User", "Register" ]);
            $view = new View($path);

            $user = new User();

            if ($_SERVER["REQUEST_METHOD"] == "POST")
            {
                $login = $queryParameters["login"]->GetValue();
                $user->SetLogin($login);
                $email = $queryParameters["email"]->GetValue();
                $user->SetEmail($email);
                $password = $queryParameters["password"]->GetValue();
                $passwordHash = hash("SHA512", $this->salt . $password);

                $userBLL = new UserBLL();
                $isLoginExists = $userBLL->IsLoginExists($login);
                $isEmailExists = $userBLL->IsEmailExists($email);

                if (!$isLoginExists && !$isEmailExists)
                {
                    $userBLL->Add($user, $passwordHash);
                    RoutesHelper::Redirect("UserLogin");
                }
            }

            return $view->Render([ "User" => $user ]);
        }
        catch (\Exception $e)
        {
            ErrorManager::Manage($e);
        }
    }
}