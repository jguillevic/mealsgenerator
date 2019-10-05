<?php

namespace Controller\User;

use Framework\View\View;
use Framework\Tools\Helper\RoutesHelper;
use Framework\Tools\Helper\PathHelper;
use BLL\User\UserBLL;
use BLL\User\FacebookUserBLL;
use Model\User\User;
use Model\User\FacebookUser;
use Tools\Helper\UserHelper;
use Framework\Controller\Violation\ViolationManager;
use Framework\Tools\Error\ErrorManager;
use Config\Facebook\FacebookConfig;

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
            $violations = new ViolationManager();

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
                        UserHelper::WebsiteLogin($user);
                        RoutesHelper::Redirect("DisplayHome");
                    }
                    else
                        $violations->AddError("Password", "Le mot de passe est erroné.");
                }
                else
                    $violations->AddError("Login", "L'identifiant n'existe pas.");
            }

            return $view->Render([ "User" => $user, "Violations" => $violations ]);
        }
        catch (\Exception $e)
        {
            ErrorManager::Manage($e);
        }
    }

    public function Logout($queryParameters)
    {
        try
        {
            if (UserHelper::IsLogin())
                UserHelper::Logout();
            
            RoutesHelper::Redirect("DisplayHome");    
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
            $violations = new ViolationManager();

            if ($_SERVER["REQUEST_METHOD"] == "POST")
            {
                $login = $queryParameters["login"]->GetValue();
                $user->SetLogin($login);
                $email = $queryParameters["email"]->GetValue();
                $user->SetEmail($email);
                //../Assets/images/icons/user/avatar-default.svg
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
                else
                {
                    if ($isLoginExists)
                        $violations->AddError("Login", "L'identifiant est déjà utilisé.");
                    if ($isEmailExists)
                        $violations->AddError("Email", "L'adresse e-mail est déjà utilisée.");
                }
            }

            return $view->Render([ "User" => $user, "Violations" => $violations ]);
        }
        catch (\Exception $e)
        {
            ErrorManager::Manage($e);
        }
    }

    public function LoginFacebook($queryParameters)
    {
        try
        {
            if (UserHelper::IsLogin())
                RoutesHelper::Redirect("DisplayHome");

            $fbConfig = new FacebookConfig();
            $fb = $fbConfig->GetFacebook();
            $helper = $fb->getJavaScriptHelper();

            $accessToken = $helper->getAccessToken();
            $responseProfile = $fb->get("/me?fields=id,first_name,last_name,email,birthday", $accessToken->GetValue());
            $responsePicture =  $fb->get("/me/picture?redirect=false", $accessToken->GetValue());
            
            $decodedBody = $responseProfile->getDecodedBody();
            $fbUser = new FacebookUser();
            $fbUser->SetFacebookId($decodedBody["id"]);
            $fbUser->SetFirstName($decodedBody["first_name"]);
            $fbUser->SetLastName($decodedBody["last_name"]);
            $fbUser->SetEmail($decodedBody["email"]);
            $fbUser->SetBirthday(new \DateTime($decodedBody["birthday"]));
            $fbUser->SetAccessToken($accessToken->GetValue());

            $decodedBody = $responsePicture->getDecodedBody();
            $fbUser->SetProfilePictureUrl($decodedBody["data"]["url"]);

            $fbBLL = new FacebookUserBLL();
            $fbBLL->AddOrUpdate($fbUser);

            UserHelper::FacebookLogin($fbUser);
            RoutesHelper::Redirect("DisplayHome");
              
        }
        catch (\Exception $e)
        {
            ErrorManager::Manage($e);
        }
    }

    public function GetLoginKind($queryParameters)
    {
        try
        {
            if (!UserHelper::IsLogin())
                RoutesHelper::Redirect("DisplayHome");
            
            return UserHelper::GetLoginKind();
        }
        catch (\Exception $e)
        {
            ErrorManager::Manage($e);
        }
    }
}