<?php

namespace Framework\Tools\Helper;

class SlugHelper
{
	public static function Slugify($text, $delimiter = '-')
	{
		$oldLocale = setlocale(LC_ALL, '0');
		setlocale(LC_ALL, 'en_US.UTF-8');
		$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $text);
		$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
		$clean = strtolower($clean);
		$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
		$clean = trim($clean, $delimiter);
		setlocale(LC_ALL, $oldLocale);
		
		return $clean;
	}
}