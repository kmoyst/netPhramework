<?php

namespace netPhramework\bootstrap;

use netPhramework\common\FileFinder;
use netPhramework\core\RequestContext;
use netPhramework\core\RequestInterpreter;
use netPhramework\core\Responder;
use netPhramework\core\Session;
use netPhramework\core\UploadManager;
use netPhramework\rendering\Encoder;

class SiteContext implements RequestContext
{
	protected readonly Environment $environment;
	protected readonly Session $session;
	protected readonly UploadManager $uploadManager;

    public function __construct(
		?Environment $environment = null,
		?Session $session = null,
		?UploadManager $uploadManager = null
	)
	{
		$this->environment = $environment ?? Environment::PRODUCTION;
        $this->session 	   = $session ?? new Session();
		$this->uploadManager = $uploadManager ?? new UploadManager();
    }

	public function getCallbackKey(): string
	{
		return 'callback';
	}

	public function getRequestInterpreter():RequestInterpreter
	{
		return new RequestInterpreter();
	}

	public function getResponder(Encoder $encoder):Responder
	{
		return new Responder($encoder);
	}

	public function getEncoder():Encoder
	{
		return new Encoder($this->configureFileFinder(new FileFinder()));
	}

	protected function configureFileFinder(FileFinder $fileFinder): FileFinder
	{
		return $fileFinder
			->directory('../html')
			->directory(__DIR__.'/../../../html')
			->extension('phtml')
			->extension('css')
			;
	}

	public function getEnvironment(): Environment
	{
		return $this->environment;
	}

	public function getSession():Session
	{
		return $this->session;
	}

	public function getUploadManager(): UploadManager
	{
		return $this->uploadManager;
	}

	public function getConfiguration():Configuration
	{
		return new Configuration();
	}
}