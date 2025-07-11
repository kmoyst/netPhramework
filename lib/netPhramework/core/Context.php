<?php

namespace netPhramework\core;

use netPhramework\authentication\Session;
use netPhramework\bootstrap\Environment;
use netPhramework\exchange\CallbackManager;
use netPhramework\exchange\ExchangeContext;
use netPhramework\exchange\RequestInterpreter;
use netPhramework\networking\SmtpServer;
use netPhramework\responding\FileManager;
use netPhramework\responding\Responder;

abstract class Context implements ExchangeContext
{
	public RequestInterpreter $interpreter;
	public CallbackManager $callbackManager;
	public Responder $responder;
	public SmtpServer $smtpServer;
	public Site $site;

	public function __construct
	(
	public Environment $environment = new Environment(),
	public Session     $session 	= new Session(),
	public FileManager $fileManager = new FileManager(),
	)
	{
		$this->site				= new Site();
		$this->interpreter 		= new RequestInterpreter($this->environment);
		$this->callbackManager 	= new CallbackManager();
		$this->responder 		= new Responder();
		$this->smtpServer		= new SmtpServer($this->environment);
	}
	public function configureResponder(Responder $responder):void
	{
		$responder->wrapper->addStyleSheet('framework-stylesheet');
		$responder->templateFinder
			->directory('../html')
			->directory(__DIR__ . '/../../../html')
			->extension('phtml')
			->extension('css')
		;
	}
	abstract public function getNodeBuilder(): NodeBuilder;
}