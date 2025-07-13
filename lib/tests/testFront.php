<?php
require_once '../netPhramework/bootstrap/Loader.php';
use netPhramework\stubs\Site;
use netPhramework\stubs\Environment;
use netPhramework\bootstrap\Controller;
$environment = new Environment();
$site 		 = new Site($environment);
$app  		 = $site->getApplication();
$controller  = new Controller($site);
$controller->run();