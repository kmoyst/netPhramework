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
	public function configureResponder(Responder $responder):void
	{
		$responder->wrapper->addStyleSheet('framework-stylesheet');
		$responder->templateFinder
			->directory('../templates')
			->directory('../html')
			->directory(__DIR__ . '/../../../templates')
			->extension('tpl')
			->extension('phtml')
			->extension('css')
		;
	}
}