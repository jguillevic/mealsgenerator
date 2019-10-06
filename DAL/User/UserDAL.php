<?php

namespace DAL\User;

use Framework\DAL\Database;
use Framework\Tools\Error\ErrorManager;
use Model\User\User;

class UserDAL
{
    private $db;
    
	public function __construct($db = null)
	{
		if (isset($db))
			$this->db = $db;
		else
			$this->db = new Database();
    }

    public function IsLoginExists($login)
    {
        try
        {
            $query = "SELECT 1 FROM User AS U WHERE U.Login = :Login;";

            $params = [ ":Login" => $login ];

            $this->db->BeginTransaction();

            $rows = $this->db->Read($query, $params);

            $this->db->Commit();

            return count($rows) > 0;
        }
        catch (\Exception $e)
        {
            $this->db->Rollback();

            ErrorManager::Manage($e);
        }
    }

    public function IsEmailExists($email)
    {
        try
        {
            $query = "SELECT 1 FROM User AS U WHERE U.Email = :Email;";

            $params = [ ":Email" => $email ];

            $this->db->BeginTransaction();

            $rows = $this->db->Read($query, $params);

            $this->db->Commit();

            return count($rows) > 0;
        }
        catch (\Exception $e)
        {
            $this->db->Rollback();

            ErrorManager::Manage($e);
        }
    }

    public function IsPasswordHashMatches($login, $passwordHash)
    {
        try
        {
            $this->db->BeginTransaction();

            $loadedPasswordHash = $this->LoadPasswordHashFromLogin($login);

            $this->db->Commit();

            if ($loadedPasswordHash != null)
                return $loadedPasswordHash == $passwordHash;

            return false;
        }
        catch (\Exception $e)
        {
            $this->db->Rollback();

            ErrorManager::Manage($e);
        }
    }

    private function LoadPasswordHashFromLogin($login)
    {
        try
        {
            $query = "SELECT U.PasswordHash
                      FROM User AS U
                      WHERE U.Login = :Login;";
        
            $params = [ ":Login" => $login ];

            $this->db->BeginTransaction();

            $rows = $this->db->Read($query, $params);

            $this->db->Commit();

            if (count($rows) > 0)
                return $rows[0]["PasswordHash"];

            return null;
        }
        catch (\Exception $e)
        {
            $this->db->Rollback();

            ErrorManager::Manage($e);
        }
    }

    public function Add($user, $passwordHash)
    {
        try
        {
            $query = "INSERT INTO User (Login, Email, PasswordHash, AvatarUrl, RememberMe)
                      VALUES (:Login, :Email, :PasswordHash, :AvatarUrl, :RememberMe);";
            
            $params = [ 
                ":Login" => $user->GetLogin()
                , ":Email" => $user->GetEmail() 
                , ":PasswordHash" => $passwordHash
                , ":AvatarUrl" => $user->GetAvatarUrl()
                , ":RememberMe" => $user->GetRememberMe()
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

    public function UpdateRememberMe($id, $rememberMe)
    {
        try
        {
            $query = "UPDATE User SET RememberMe = :RememberMe WHERE Id = :Id;";

            $params = [
                ":Id" => $id
                , ":RememberMe" => $rememberMe
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

    public function LoadFromLogin($login)
    {
        try
        {
            $query = "SELECT U.Id, U.Login, U.Email, U.AvatarUrl, U.RememberMe FROM User AS U WHERE U.Login = :Login;";

            $params = [
                ":Login" => $login
            ];

            $this->db->BeginTransaction();

            $rows = $this->db->Read($query, $params);

            $this->db->Commit();

            if (count($rows) > 0)
            {
                $row = $rows[0];

                $user = new User();
                $user->SetId($row["Id"]);
                $user->SetLogin($row["Login"]);
                $user->SetEmail($row["Email"]);
                $user->SetAvatarUrl($row["AvatarUrl"]);
                $user->SetRememberMe($row["RememberMe"]);

                return $user;
            }

            return null;
        }
        catch (\Exception $e)
        {
            $this->db->Rollback();

            ErrorManager::Manage($e);
        } 
    }
}