<?php

namespace Tools\Helper;

use Model\User\User;
use Model\User\FacebookUser;

/**
 * @author JGuillevic
 */
class UserHelper
{
	const WEBSITE_USER_KEY = "WebsiteUser";
	const FACEBOOK_USER_KEY = "FacebookUser";

	public static function IsLogin()
	{
		return self::IsWebsiteLogin() || self::IsFacebookLogin();
	}

	public static function IsWebsiteLogin()
	{
		return self::IsLoginInternal(self::WEBSITE_USER_KEY);
	}

	public static function IsFacebookLogin()
	{
		return self::IsLoginInternal(self::FACEBOOK_USER_KEY);
	}

	private static function IsLoginInternal($key)
	{
		return array_key_exists($key, $_SESSION) && isset($_SESSION[$key]);
	}

	private static function GetWebsiteUser()
	{
		$user = null;

		if (self::IsWebsiteLogin())
			$user = json_decode($_SESSION[self::WEBSITE_USER_KEY]);

		return $user;
	}

	private static function GetFacebookUser()
	{
		$user = null;

		if (self::IsFacebookLogin())
			$user = json_decode($_SESSION[self::FACEBOOK_USER_KEY]);

		return $user;
	}

	public static function WebsiteLogin($websiteUser)
	{
		$_SESSION[self::WEBSITE_USER_KEY] = json_encode($websiteUser);
	}

	public static function FacebookLogin($facebookUser)
	{
		$_SESSION[self::FACEBOOK_USER_KEY] = json_encode($facebookUser);
	}

	public static function Logout()
	{
		if (self::IsWebsiteLogin())
		{
			unset($_SESSION[self::WEBSITE_USER_KEY]);

			return true;
		}
		else if (self::IsFacebookLogin())
		{
			unset($_SESSION[self::FACEBOOK_USER_KEY]);

			return true;
		}
		
		return false;
	}

	public static function GetLoginKind()
	{
		if (self::IsWebsiteLogin())
			return "WEBSITE";
		else if (self::IsFacebookLogin())
			return "FACEBOOK";
		
		return null;
	}

	public static function GetName()
	{
		if (self::IsWebsiteLogin())
		{
			$wsUser = self::GetWebsiteUser();
			return $wsUser->Login;
		}
		else if (self::IsFacebookLogin())
		{
			$fbUser = self::GetFacebookUser();
			return $fbUser->FirstName;
		}

		return null;
	}

	public static function GetAvatarUrl()
	{
		if (self::IsWebsiteLogin())
		{
			$wsUser = self::GetWebsiteUser();
			return $wsUser->AvatarUrl;
		}
		else if (self::IsFacebookLogin())
		{
			$fbUser = self::GetFacebookUser();
			return $fbUser->ProfilePictureUrl;
		}
		
		return null;
	}
}