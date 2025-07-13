<?php
require_once '../netPhramework/bootstrap/Loader.php';
use netPhramework\stubs\Site;
use netPhramework\stubs\TestEnvironment;
use netPhramework\bootstrap\Controller;
$environment = new TestEnvironment();
$site 		 = new Site($environment);
$app  		 = $site->getApplication();
$controller  = new Controller($site);
$controller->run();