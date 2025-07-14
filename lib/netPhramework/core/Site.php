<?php

namespace netPhramework\core;

use netPhramework\exchange\HttpResponder;
use netPhramework\exchange\HttpServices;
use netPhramework\exchange\HttpInterpreter;
use netPhramework\exchange\Interpreter;
use netPhramework\exchange\Responder;
use netPhramework\exchange\Services;

abstract class Site
{
	public function __construct
	(
	public Environment $environment = new WebEnvironment(),
	public Interpreter $interpreter = new HttpInterpreter(),
	public Responder   $responder   = new HttpResponder(),
	public Services $services	= new HttpServices()
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

	abstract public function getApplication():Application;
}