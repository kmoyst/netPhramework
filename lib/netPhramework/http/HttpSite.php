<?php

namespace netPhramework\http;

use netPhramework\core\Application;
use netPhramework\core\Environment;
use netPhramework\core\Site;
use netPhramework\exchange\Interpreter;
use netPhramework\exchange\Responder;
use netPhramework\exchange\Services;

abstract class HttpSite implements Site
{
	protected(set) Environment $environment;
	protected(set) Interpreter $interpreter;
	protected(set) Responder   $responder;
	protected(set) Services    $services;

	public function __construct()
	{
		$this->environment = new HttpEnvironment();
		$this->interpreter = new HttpInterpreter();
		$this->responder   = new HttpResponder();
		$this->services	   = new HttpServices();
		$this->services->smtpServer->initialize($this->environment);
	}

	public function configureResponder(Responder $responder): void
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