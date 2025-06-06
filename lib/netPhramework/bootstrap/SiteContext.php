<?php

namespace netPhramework\bootstrap;

use netPhramework\common\FileFinder;
use netPhramework\core\RequestContext;
use netPhramework\core\RequestInterpreter;
use netPhramework\core\Session;
use netPhramework\core\FileManager;
use netPhramework\rendering\Encoder;
use netPhramework\responding\Responder;

class SiteContext implements RequestContext
{
	protected readonly Session 	   $session;
	protected readonly FileManager $fileManager;
	protected readonly Environment $environment;

	public function __construct(
		?Environment $environment = null,
		?Session     $session = null,
		?FileManager $fileManager = null
	)
	{
		$this->environment	= $environment 	?? new Environment();
		$this->session 		= $session 		?? new Session();
		$this->fileManager 	= $fileManager 	?? new FileManager($_FILES);
	}

	public function getCallbackKey(): string
	{
		return 'callback';
	}

	public function getRequestInterpreter(): RequestInterpreter
	{
		return new RequestInterpreter();
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

	public function getConfiguration(): Configuration
	{
		return new Configuration();
	}
}