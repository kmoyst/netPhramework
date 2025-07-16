<?php

namespace netPhramework\core;

use netPhramework\exchange\Request;
use netPhramework\exchange\Responder;
use netPhramework\exchange\Services;

abstract class Site
{
	protected(set) Request	 $request;
	protected(set) Responder $responder;
	protected(set) Services  $services;
	protected(set) Environment $environment;
	abstract public Application $application {get;}

	public function __construct(Context $context)
	{
		$this->request	   = $context->request;
		$this->responder   = $context->responder;
		$this->services	   = $context->services;
		$this->environment = $context->environment;
	}

	public function configure():void
	{
		$this->responder->wrapper->addStyleSheet('framework-stylesheet');
		$this->responder->application = $this->application;
		$this->responder->services = $this->services;
		$this->responder->environment = $this->environment;
	}
}
