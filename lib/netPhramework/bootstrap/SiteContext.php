<?php

namespace netPhramework\bootstrap;

use netPhramework\common\FileFinder;
use netPhramework\core\RequestContext;
use netPhramework\core\RequestInterpreter;
use netPhramework\core\Session;
use netPhramework\rendering\Encoder;
use netPhramework\responding\Displayer;
use netPhramework\responding\Redirector;
use netPhramework\responding\Responder;

class SiteContext implements RequestContext
{
	protected readonly Environment $environment;
	protected readonly Session $session;

	protected string $customTemplateDir = '../html';
	protected string $baseTemplateDir 	= __DIR__.'/../../../html';
	protected string $templateExtension = "phtml";
	protected string $callbackKey 		= "callback";

    public function __construct(
		?Environment $environment = null, ?Session $session = null)
	{
		$this->environment = $environment ?? Environment::PRODUCTION;
        $this->session 	   = $session ?? new Session();
    }

	public function getCallbackKey(): string
	{
		return $this->callbackKey;
	}

	public function getRequestInterpreter():RequestInterpreter
	{
		return new RequestInterpreter();
	}

	public function getResponder(Encoder $encoder):Responder
	{
		return new Responder(new Displayer($encoder), new Redirector($encoder));
	}

	public function getEncoder():Encoder
	{
		return new Encoder($this->configureFileFinder(new FileFinder()));
	}

	protected function configureFileFinder(FileFinder $fileFinder): FileFinder
	{
		return $fileFinder
			->directory($this->customTemplateDir)
			->directory($this->baseTemplateDir)
			->extension($this->templateExtension)
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

	public function getConfiguration():Configuration
	{
		return new Configuration();
	}
}