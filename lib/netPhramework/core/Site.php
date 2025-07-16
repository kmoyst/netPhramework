<?php

namespace netPhramework\core;

use netPhramework\exchange\Request;
use netPhramework\exchange\Responder;
use netPhramework\exchange\Services;

abstract class Site
{
	private(set) Request   $request {get{
		if(!isset($this->request))
			$this->request = $this->context->request;
		return $this->request;
	}set{}}

	private(set) Responder $responder{get{
		if(!isset($this->responder))
			$this->responder = $this->context->responder;
		return $this->responder;
	}set{}}

	private(set) Services  $services{get{
		if(!isset($this->services))
			$this->services = $this->context->services;
		return $this->services;
	}set{}}

	private(set) Environment $environment{get{
		if(!isset($this->environment))
			$this->environment = $this->context->environment;
		return $this->environment;
	}set{}}

	abstract public Application $application {get;}

	public function __construct(protected readonly Context $context) {}

	public function configure():void
	{
		$this->responder->application 	= $this->application;
		$this->responder->services 		= $this->services;
		$this->responder->environment 	= $this->environment;
		$this->context->configureResponder($this->responder);
	}
}
