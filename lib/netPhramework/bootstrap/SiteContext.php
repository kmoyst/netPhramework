<?php

namespace netPhramework\bootstrap;

use netPhramework\common\FileFinder;
use netPhramework\core\CallbackManager;
use netPhramework\core\RequestContext;
use netPhramework\core\RequestInterpreter;
use netPhramework\core\Session;
use netPhramework\core\FileManager;
use netPhramework\rendering\Encoder;
use netPhramework\responding\Responder;

abstract class SiteContext implements RequestContext
{
	public readonly RequestInterpreter $requestInterpreter;
	public readonly CallbackManager $callbackManager;
	public readonly Responder $responder;

	public function __construct
	(
	public readonly Environment $environment = new Environment(),
	public readonly Session 	$session 	 = new Session(),
	public readonly FileManager $fileManager = new FileManager(),
	)
	{
		$this->requestInterpreter = new RequestInterpreter($this->environment);
		$this->callbackManager 	  = new CallbackManager('callback');
		$fileFinder = new FileFinder()
			->directory('../html')
			->directory(__DIR__ . '/../../../html')
			->extension('phtml')
			->extension('css')
		;
		$this->responder = new Responder(new Encoder($fileFinder));
	}

	abstract public function getApplication(): Application;
}