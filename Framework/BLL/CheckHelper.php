<?php

namespace Framework\BLL;

class CheckHelper
{
	const DEFAULT_MIN_LENGTH = 1;
	const MIN_LENGTH_MESSAGE = "Le champ \"%s\" doit contenir au moins %s caractère(s).";
	const MAX_LENGTH_MESSAGE = "Le champ \"%s\" doit contenir moins de %s caractère(s).";

	public static function CheckStringLength($string, $minLength, $minLengthMessage, $maxLength, $maxLengthMessage)
	{
		if (!isset($string) || \mb_strlen($string) < $minLength)
		{
			return $minLengthMessage;
		}
		else if (\mb_strlen($string) > $maxLength)
		{
			return $maxLengthMessage;
		}

		return true;
	}
}