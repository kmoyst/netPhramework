<?php

namespace netPhramework\site;

use netPhramework\authentication\Session;
use netPhramework\bootstrap\Environment;
use netPhramework\exchange\RequestContext;
use netPhramework\exchange\RequestInterpreter;
use netPhramework\exchange\Responder;
use netPhramework\routing\CallbackManager;
use netPhramework\transferring\FileManager;
use netPhramework\transferring\SmtpServer;

abstract class Context implements RequestContext
{
	public CallbackManager $callbackManager;
	public Responder $responder;
	public SmtpServer $smtpServer;
	public RequestInterpreter $requestProcessor;

	public function __construct
	(
	public Environment $environment = new Environment(),
	public Session     $session 	= new Session(),
	public FileManager $fileManager = new FileManager(),
	)
	{
		$this->callbackManager 	= new CallbackManager();
		$this->responder 		= new Responder();
		$this->smtpServer		= new SmtpServer($this->environment);
		$this->requestProcessor = new RequestInterpreter();
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
}