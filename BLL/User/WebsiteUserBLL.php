<?php

namespace BLL\User;

use Framework\DAL\Database;
use Framework\Tools\Error\ErrorManager;
use DAL\User\WebsiteUserDAL;

class WebsiteUserBLL
{
    public function IsLoginExists(string $login) : bool
    {
        try
        {
            $db = new Database();
            $db->BeginTransaction();

            $wuDAL = new WebsiteUserDAL($db);
            $isLoginExists = $wuDAL->IsLoginExists($login);

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

    public function IsEmailExists(string $login) : bool
    {
        try
        {
            $db = new Database();
            $db->BeginTransaction();

            $wuDAL = new WebsiteUserDAL($db);
            $isEmailExists = $wuDAL->IsEmailExists($login);

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

    public function IsPasswordHashMatches(string $login, string $passwordHash) : bool
    {
        try
        {
            $db = new Database();
            $db->BeginTransaction();

            $wuDAL = new WebsiteUserDAL($db);
            $isPasswordHashMatches = $wuDAL->IsPasswordHashMatches($login, $passwordHash);

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

    public function Add(WebsiteUser $websiteUser, string $passwordHash) : void
    {
        try
        {
            $db = new Database();
            $db->BeginTransaction();

            $wuDAL = new WebsiteUserDAL($db);
            $wuDAL->Add($websiteUser, $passwordHash);

            $db->Commit();

        }
        catch (\Exception $e)
        {
            if ($db != null)
                $db->Rollback();

            ErrorManager::Manage($e);
        }
    }

    public function LoadFromLogin(string $login) : ?WebsiteUser
    {
        try
        {
            $db = new Database();
            $db->BeginTransaction();

            $wuDAL = new WebsiteUserDAL($db);
            $websiteUser = $wuDAL->LoadFromLogin($login);

            $db->Commit();

            return $websiteUser;
        }
        catch (\Exception $e)
        {
            if ($db != null)
                $db->Rollback();

            ErrorManager::Manage($e);
        }
    }
}