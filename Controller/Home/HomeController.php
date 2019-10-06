<?php

namespace Controller\Home;

use Framework\View\View;
use Framework\Tools\Helper\PathHelper;
use Framework\Tools\Helper\RoutesHelper;

class HomeController
{
	public function Display($queryParameters)
	{
		$path = PathHelper::GetPath([ "Home", "Display" ]);
		$view = new View($path);
		
		return $view->Render();
	}
}