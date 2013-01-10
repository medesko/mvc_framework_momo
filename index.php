<?php

$framework = dirname(__FILE__).'/framework/framework.php';
$config = dirname(__FILE__).'/app/config/main.php';

require_once($framework);

$app = new Framework($config);
$app->run();

?>