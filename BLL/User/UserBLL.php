<?php

namespace BLL\User;

use Framework\DAL\Database;
use Framework\Tools\Error\ErrorManager;
use DAL\User\UserDAL;

class UserBLL
{
    public function IsLoginExists($login)
    {
        try
        {
            $db = new Database();
            $db->BeginTransaction();

            $userDAL = new UserDAL($db);
            $isLoginExists = $userDAL->IsLoginExists($login);

            $db->Commit();

            return $isLoginExists;
        }
        catch (\Exception $e)
        {
            if ($db != null)
                $db->Rollback();

            ErrorManager::Manage($e);
        }
    }

    public function IsEmailExists($login)
    {
        try
        {
            $db = new Database();
            $db->BeginTransaction();

            $userDAL = new UserDAL($db);
            $isEmailExists = $userDAL->IsEmailExists($login);

            $db->Commit();

            return $isEmailExists;
        }
        catch (\Exception $e)
        {
            if ($db != null)
                $db->Rollback();

            ErrorManager::Manage($e);
        }
    }

    public function IsPasswordHashMatches($login, $passwordHash)
    {
        try
        {
            $db = new Database();
            $db->BeginTransaction();

            $userDAL = new UserDAL($db);
            $isPasswordHashMatches = $userDAL->IsPasswordHashMatches($login, $passwordHash);

            $db->Commit();

            return $isPasswordHashMatches;
        }
        catch (\Exception $e)
        {
            if ($db != null)
                $db->Rollback();

            ErrorManager::Manage($e);
        }
    }

    public function Add($user, $passwordHash)
    {
        try
        {
            $db = new Database();
            $db->BeginTransaction();

            $userDAL = new UserDAL($db);
            $userDAL->Add($user, $passwordHash);

            $db->Commit();

        }
        catch (\Exception $e)
        {
            if ($db != null)
                $db->Rollback();

            ErrorManager::Manage($e);
        }
    }

    public function LoadFromLogin($login)
    {
        try
        {
            $db = new Database();
            $db->BeginTransaction();

            $userDAL = new UserDAL($db);
            $userDAL->LoadFromLogin($login);

            $db->Commit();
        }
        catch (\Exception $e)
        {
            if ($db != null)
                $db->Rollback();

            ErrorManager::Manage($e);
        }
    }
}