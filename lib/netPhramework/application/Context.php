<?php

namespace netPhramework\application;

use netPhramework\authentication\Session;
use netPhramework\bootstrap\Environment;
use netPhramework\exchange\CallbackManager;
use netPhramework\exchange\ExchangeContext;
use netPhramework\exchange\RequestInterpreter;
use netPhramework\networking\SmtpServer;
use netPhramework\responding\FileManager;
use netPhramework\responding\Responder;

abstract readonly class Context implements ExchangeContext
{
	public RequestInterpreter $interpreter;
	public CallbackManager $callbackManager;
	public Responder $responder;
	public SmtpServer $smtpServer;

	public function __construct
	(
	public Environment $environment = new Environment(),
	public Session     $session 	= new Session(),
	public FileManager $fileManager = new FileManager(),
	)
	{
		$this->interpreter 		= new RequestInterpreter($this->environment);
		$this->callbackManager 	= new CallbackManager();
		$this->responder 		= new Responder();
		$this->smtpServer		= new SmtpServer($this->environment);
	}
	abstract public function getConfigurator(): Configurator;
}