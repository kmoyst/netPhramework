<?php
require_once '../../lib/netPhramework/bootstrap/Loader.php';

use stubs\TestEnvironment;
use netPhramework\bootstrap\Controller;
use netPhramework\exchange\Responder;
use netPhramework\rendering\PlainTextEncoder;
use sample\Site;

$site 				= new Site(new TestEnvironment());
$site->responder 	= new Responder(new PlainTextEncoder());

$controller  = new Controller($site);
$controller->run();