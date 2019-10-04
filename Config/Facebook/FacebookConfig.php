<?php

namespace Config\Facebook;

class FacebookConfig
{
    private $fbConfig;

	public function __construct()
	{
        $this->fbConfig = self::GetConfig();
	}

    private static function GetConfig()
	{
		$configPath = join(DIRECTORY_SEPARATOR, [ __DIR__, "FacebookConfig.json" ]);

		$json = file_get_contents($configPath);

        $config = json_decode($json, true);
        
		return $config;
    }
    
    public function GetFacebook()
    {
        return new \Facebook\Facebook([
            "app_id" => $this->fbConfig["AppId"]
            , "app_secret" => $this->fbConfig["AppSecret"]
            , "default_graph_version" => "v4.0"
        ]);
    }
}