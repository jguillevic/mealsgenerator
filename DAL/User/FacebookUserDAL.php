<?php

namespace DAL\User;

use Framework\DAL\Database;
use Framework\Tools\Error\ErrorManager;
use Model\User\FacebookUser;

class FacebookUserDAL
{
    private $db;
    
	public function __construct($db = null)
	{
		if (isset($db))
			$this->db = $db;
		else
			$this->db = new Database();
    }

    public function AddOrUpdate($facebookUser)
    {
        try
        {
            $query = "INSERT INTO FacebookUser (FacebookId, FirstName, LastName, Email, Birthday, ProfilePictureUrl, AccessToken)
                      VALUES (:FacebookId, :FirstName, :LastName, :Email, :Birthday, :ProfilePictureUrl, :AccessToken)
                      ON DUPLICATE KEY UPDATE 
                      FirstName = :FirstName
                      , LastName = :LastName
                      , Email = :Email
                      , Birthday = :Birthday
                      , ProfilePictureUrl = :ProfilePictureUrl
                      , AccessToken = :AccessToken;";

            $params = [ 
                ":FacebookId" => $facebookUser->GetFacebookId()
                , ":FirstName" => $facebookUser->GetFirstName() 
                , ":LastName" => $facebookUser->GetLastName()
                , ":Email" => $facebookUser->GetEmail()
                , ":Birthday" => $facebookUser->GetBirthday()->format("Y-m-d")
                , ":ProfilePictureUrl" => $facebookUser->GetProfilePictureUrl()
                , ":AccessToken" => $facebookUser->GetAccessToken()
            ];

            $this->db->BeginTransaction();

            $this->db->Execute($query, $params);

            $this->db->Commit();
        }
        catch (\Exception $e)
        {
            $this->db->Rollback();

            ErrorManager::Manage($e);
        }
    }
}