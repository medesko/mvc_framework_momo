<?php

// DEBUG
// ini_set('display_errors', 1);
// error_reporting(E_ALL);


$framework = dirname(__FILE__).'/framework/framework.php';
$config = dirname(__FILE__).'/app/config/main.php';

require_once($framework);

$app = new Framework($config);
$app->run();

?>