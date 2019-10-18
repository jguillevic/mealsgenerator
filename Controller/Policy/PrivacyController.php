<?php

namespace Controller\Policy;

use Framework\View\View;
use Framework\Tools\Helper\PathHelper;
use Framework\Tools\Helper\RoutesHelper;

class PrivacyController
{
    public function Display($queryParameters)
	{
		try
		{
			$path = PathHelper::GetPath([ "Policy", "Privacy", "Display" ]);
			$view = new View($path);
			
			return $view->Render();
		}
		catch (\Exception $e)
		{
			ErrorManager::Manage($e);
		}
	}
}