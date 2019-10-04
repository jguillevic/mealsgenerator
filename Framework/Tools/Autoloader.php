<?php

namespace Framework\Tools;

class Autoloader
{
	public function Run()
	{
		return spl_autoload_register([$this, "LoadClass"]);
	}

	private function LoadClass($fullClassName)
	{
		$classNameItems = explode('\\', $fullClassName);
		$path = join(DIRECTORY_SEPARATOR, $classNameItems);
		$path = join(DIRECTORY_SEPARATOR, array(__DIR__, "..", "..", $path . ".php"));
		
		if (file_exists($path))
		{
			require_once($path);
			return true;
		}

		return false;
	}
}