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

class SiteContext implements RequestContext
{
	public function __construct
	(
	protected readonly Environment $environment = new Environment(),
	protected readonly Session 	   $session 	= new Session(),
	protected readonly FileManager $fileManager = new FileManager()
	)
	{}

	public function getCallbackManager(): CallbackManager
	{
		return new CallbackManager('callback');
	}

	public function getRequestInterpreter(): RequestInterpreter
	{
		return new RequestInterpreter($this->environment);
	}

	public function getResponder(Encoder $encoder): Responder
	{
		return new Responder($encoder);
	}

	public function getEncoder(): Encoder
	{
		return new Encoder($this->configureFileFinder(new FileFinder()));
	}

	protected function configureFileFinder(FileFinder $fileFinder): FileFinder
	{
		return $fileFinder
			->directory('../html')
			->directory(__DIR__ . '/../../../html')
			->extension('phtml')
			->extension('css');
	}

	public function getEnvironment(): Environment
	{
		return $this->environment;
	}

	public function getSession(): Session
	{
		return $this->session;
	}

	public function getFileManager(): FileManager
	{
		return $this->fileManager;
	}

	public function getConfiguration(): Application
	{
		return new Application();
	}
}