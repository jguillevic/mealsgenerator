<?php 

session_start();

setlocale(LC_ALL, "French");

$path = join(DIRECTORY_SEPARATOR, array(__DIR__, 'Framework', 'App.php'));
include($path);
use \Framework\App;

$app = new App();
echo $app->Run();