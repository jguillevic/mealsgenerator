<?php

namespace Model\User;

class WebsiteUser implements \JsonSerializable
{
    private $id = -1;
    private $login = "";
    private $email = "";
    private $avatarUrl = "";

    public function GetId() : int
    {
        return $this->id;
    }

    public function SetId(int $id) : WebsiteUser
    {
        $this->id = $id;

        return $this;
    }

    public function GetLogin() : string 
    {
        return $this->login;
    }

    public function SetLogin(string $login) : WebsiteUser
    {
        $this->login = $login;

        return $this;
    }

    public function GetEmail() : string
    {
        return $this->email;
    }

    public function SetEmail(string $email) : WebsiteUser
    {
        $this->email = $email;

        return $this;
    }

    public function GetAvatarUrl() : string
    {
        return $this->avatarUrl;
    }

    public function SetAvatarUrl(string $avatarUrl) : WebsiteUser
    {
        $this->avatarUrl = $avatarUrl;

        return $this;
    }

    public function jsonSerialize() : array
    {
        return [
            "Id" => $this->GetId()
            , "Login" => $this->GetLogin()
            , "Email" => $this->GetEmail()
            , "AvatarUrl" => $this->GetAvatarUrl()
        ];
    }
}