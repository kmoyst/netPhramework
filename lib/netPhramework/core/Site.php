<?php

namespace netPhramework\core;

use netPhramework\authentication\Session;
use netPhramework\bootstrap\Environment;
use netPhramework\bootstrap\WebEnvironment;
use netPhramework\exchange\RequestInterpreter;
use netPhramework\exchange\Responder;
use netPhramework\routing\CallbackManager;
use netPhramework\transferring\FileManager;
use netPhramework\transferring\SmtpServer;

abstract class Site
{
	public function __construct
	(
	public Environment $environment 			  = new WebEnvironment(),
	public RequestInterpreter $requestInterpreter = new RequestInterpreter(),
	public Responder   $responder   			  = new Responder(),
	public Session     $session 				  = new Session(),
	public FileManager $fileManager 			  = new FileManager(),
	public CallbackManager $callbackManager 	  = new CallbackManager(),
	public SmtpServer $smtpServer 				  = new SmtpServer(),
	)
	{
		$this->smtpServer->initialize($this->environment);
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