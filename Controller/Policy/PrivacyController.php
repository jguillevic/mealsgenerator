<?php

namespace Controller\Policy;

use Framework\View\View;
use Framework\Tools\Helper\PathHelper;
use Framework\Tools\Helper\RoutesHelper;

class PrivacyController
{
    public function Display($queryParameters)
	{
		$path = PathHelper::GetPath([ "Policy", "Privacy", "Display" ]);
		$view = new View($path);
		
		return $view->Render();
	}
}