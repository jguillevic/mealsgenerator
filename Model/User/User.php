<?php

namespace Model\User;

class User implements \JsonSerializable
{
    private $id;
    private $login;
    private $email;
    private $avatarUrl;
    private $rememberMe = 0;

    public function GetId()
    {
        return $this->id;
    }

    public function SetId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function GetLogin()
    {
        return $this->login;
    }

    public function SetLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    public function GetEmail()
    {
        return $this->email;
    }

    public function SetEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function GetAvatarUrl()
    {
        return $this->avatarUrl;
    }

    public function SetAvatarUrl($avatarUrl)
    {
        $this->avatarUrl = $avatarUrl;

        return $this;
    }

    public function GetRememberMe()
    {
        return $this->rememberMe;
    }

    public function SetRememberMe($rememberMe)
    {
        $this->rememberMe = $rememberMe;

        return $this;
    }

    public function jsonSerialize() 
    {
        return [
            "Id" => $this->GetId()
            , "Login" => $this->GetLogin()
            , "Email" => $this->GetEmail()
            , "AvatarUrl" => $this->GetAvatarUrl()
        ];
    }
}