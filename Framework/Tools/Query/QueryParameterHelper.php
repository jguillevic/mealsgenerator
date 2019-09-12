<?php

namespace Framework\Tools\Query;

use \Framework\Tools\Query\QueryParameter;

class QueryParameterHelper
{
	public static function GetQueryParametersFromQuery($query)
	{
		$parameters = array();

		$queryItems = explode("&", $query);

		foreach ($queryItems as $queryItem) 
		{
			$explode = explode("=", $queryItem);
			$key = $explode[0];
			$value = $explode[1];
			$queryParameter = new QueryParameter($key, $value);
			$parameters[$key] = $queryParameter;
		}

		return $parameters;
	}

	public static function GetQueryParametersFromPost($post)
	{
		$parameters = array();

		foreach ($post as $key => $value) 
		{
			$queryParameter = new QueryParameter($key, $value);
			$parameters[$key] = $queryParameter;
		}

		return $parameters;
	}
}