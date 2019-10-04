<?php

namespace Controller\User;

use Framework\View\View;
use Framework\Tools\Helper\RoutesHelper;
use Framework\Tools\Helper\PathHelper;
use BLL\User\UserBLL;
use Model\User\User;
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
                        UserHelper::Login($user);
                        RoutesHelper::Redirect("DisplayHome");
                    }
                    else
                        $violations->AddError("Password", "Le mot de passe est erroné.");
                }
                else
                    $violations->AddError("Login", "L'identifiant n'existe pas.");
            }

            $fbConfig = new FacebookConfig();
            $fb = $fbConfig->GetFacebook();
            $helper = $fb->getRedirectLoginHelper();
            $permissions = ['email'];
            $facebookLoginUrl = $helper->getLoginUrl("https://mealsgenerator.local/User/Login/Facebook/Callback", $permissions);

            return $view->Render([ "User" => $user, "Violations" => $violations, "FacebookLoginUrl" => $facebookLoginUrl ]);
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

    public function LoginFacebookCallback($queryParameters)
    {
        try
        {
            if (UserHelper::IsLogin())
                RoutesHelper::Redirect("DisplayHome");

            $fbConfig = new FacebookConfig();
            $fb = $fbConfig->GetFacebook();
            $helper = $fb->getRedirectLoginHelper();

            try {
                $accessToken = $helper->getAccessToken();
                $response = $fb->get('/me?fields=id,name', $accessToken->GetValue());
                print_r($response);
              } catch(Facebook\Exceptions\FacebookResponseException $e) {
                // When Graph returns an error
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
              } catch(Facebook\Exceptions\FacebookSDKException $e) {
                // When validation fails or other local issues
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
              }
              
              if (! isset($accessToken)) {
                if ($helper->getError()) {
                  header('HTTP/1.0 401 Unauthorized');
                  echo "Error: " . $helper->getError() . "\n";
                  echo "Error Code: " . $helper->getErrorCode() . "\n";
                  echo "Error Reason: " . $helper->getErrorReason() . "\n";
                  echo "Error Description: " . $helper->getErrorDescription() . "\n";
                } else {
                  header('HTTP/1.0 400 Bad Request');
                  echo 'Bad request';
                }
                exit;
              }
              
              // Logged in
              echo '<h3>Access Token</h3>';
              var_dump($accessToken->getValue());
              
              // The OAuth 2.0 client handler helps us manage access tokens
              $oAuth2Client = $fb->getOAuth2Client();
              
              // Get the access token metadata from /debug_token
              $tokenMetadata = $oAuth2Client->debugToken($accessToken);
              echo '<h3>Metadata</h3>';
              var_dump($tokenMetadata);
              
              // Validation (these will throw FacebookSDKException's when they fail)
              //$tokenMetadata->validateAppId('{app-id}'); // Replace {app-id} with your app id
              // If you know the user ID this access token belongs to, you can validate it here
              //$tokenMetadata->validateUserId('123');
              $tokenMetadata->validateExpiration();
              
              if (! $accessToken->isLongLived()) {
                // Exchanges a short-lived access token for a long-lived one
                try {
                  $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
                } catch (Facebook\Exceptions\FacebookSDKException $e) {
                  echo "<p>Error getting long-lived access token: " . $e->getMessage() . "</p>\n\n";
                  exit;
                }
              
                echo '<h3>Long-lived</h3>';
                var_dump($accessToken->getValue());
              }
              
              $_SESSION['fb_access_token'] = (string) $accessToken;
              
        }
        catch (\Exception $e)
        {
            ErrorManager::Manage($e);
        }
    }
}