<?php

namespace Tools\Helper;

use \Model\User\User;

/**
 * @author JGuillevic
 */
class UserHelper
{
	const USER_KEY = "user";

	public static function IsLogin()
	{
		return isset($_SESSION[self::USER_KEY]);
	}

	private static function GetUser()
	{
		$user = null;

		if (self::IsLogin())
		{
			$user = $_SESSION[self::USER_KEY];
		}

		return $user;
	}

	public static function Login($user)
	{
		$_SESSION[self::USER_KEY] = $user;
	}

	public static function Logout()
	{
		if (self::IsLogin())
		{
			unset($_SESSION[self::USER_KEY]);

			return true;
		}
		
		return false;
	}
}