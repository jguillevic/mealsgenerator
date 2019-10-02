<?php

namespace DAL\User;

use Framework\DAL\Database;
use Framework\Tools\Error\ErrorManager;

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

    public function IsPasswordHashMatches($login, $passwordHash)
    {
        try
        {
            $loadedPasswordHash = LoadPasswordHashFromLogin($login);

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
            $query = "INSERT INTO User (Login, Email, PasswordHash, RememberMe)
                      VALUES (:Login, :Email, :PasswordHash, :RememberMe);";
            
            $params = [ 
                ":Login" => $user->GetLogin()
                , ":Email" => $user->GetEmail() 
                , ":PasswordHash" => $passwordHash
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
        }
        catch (\Exception $e)
        {
            $this->db->Rollback();

            ErrorManager::Manage($e);
        } 
    }
}