<?php

namespace Model\User;

class FacebookUser implements \JsonSerializable
{
    private $facebookId;
    private $firstName;
    private $lastName;
    private $email;
    private $birthday;
    private $profilePictureUrl;
    private $accessToken;

    public function GetFacebookId()
    {
        return $this->facebookId;
    }

    public function SetFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;

        return $this;
    }

    public function GetFirstName()
    {
        return $this->firstName;
    }

    public function SetFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function GetLastName()
    {
        return $this->lastName;
    }

    public function SetLastName($lastName)
    {
        $this->lastName = $lastName;

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

    public function GetBirthday()
    {
        return $this->birthday;
    }

    public function SetBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function GetProfilePictureUrl()
    {
        return $this->profilePictureUrl;
    }

    public function SetProfilePictureUrl($profilePictureUrl)
    {
        $this->profilePictureUrl = $profilePictureUrl;

        return $this;
    }

    public function GetAccessToken()
    {
        return $this->accessToken;
    }

    public function SetAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    public function jsonSerialize() 
    {
        return [
            "FacebookId" => $this->GetFacebookId()
            , "FirstName" => $this->GetFirstName()
            , "LastName" => $this->GetLastName()
            , "Email" => $this->GetEmail()
            , "Birthday" => $this->GetBirthday()->format("Y-m-d")
            , "ProfilePictureUrl" => $this->GetProfilePictureUrl()
            , "AccessToken" => $this->GetAccessToken()
        ];
    }
}