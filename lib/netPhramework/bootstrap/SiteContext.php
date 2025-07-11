<?php

namespace netPhramework\bootstrap;

use netPhramework\core\CallbackManager;
use netPhramework\core\RequestContext;
use netPhramework\core\RequestInterpreter;
use netPhramework\core\Session;
use netPhramework\core\FileManager;
use netPhramework\responding\Responder;

abstract class SiteContext implements RequestContext
{
	public readonly RequestInterpreter $interpreter;
	public readonly CallbackManager $callbackManager;
	public readonly Responder $responder;

	public function __construct
	(
	public readonly Environment $environment = new Environment(),
	public readonly Session 	$session 	 = new Session(),
	public readonly FileManager $fileManager = new FileManager(),
	)
	{
		$this->interpreter 		= new RequestInterpreter($this->environment);
		$this->callbackManager 	= new CallbackManager();
		$this->responder 		= new Responder();
	}
	abstract public function getApplication(): Application;
}