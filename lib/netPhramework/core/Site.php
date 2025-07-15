<?php

namespace netPhramework\core;

use netPhramework\exchange\Interpreter;
use netPhramework\exchange\Responder;
use netPhramework\exchange\Services;

abstract class Site
{
	abstract public Application $application {get;}

	public function __construct
	(
	public Environment $environment,
	public Interpreter $interpreter,
	public Responder   $responder,
	public Services    $services,
	)
	{
		$this->services->smtpServer->initialize($this->environment);
	}
	abstract public function configureResponder(Responder $responder):void;
}