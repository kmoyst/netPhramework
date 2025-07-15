<?php

namespace netPhramework\core;

use netPhramework\exceptions\InvalidSession;
use netPhramework\exchange\Request;
use netPhramework\exchange\Responder;
use netPhramework\exchange\Services;

abstract class Site
{
	protected(set) Request	 $request;
	protected(set) Responder $responder;
	protected(set) Services  $services;
	protected(set) Environment $env;
	abstract public Application $application {get;}

	private Configurator $config;

	public function __construct(Context $context)
	{
		$this->request	 = $context->request;
		$this->responder = $context->responder;
		$this->services	 = $context->services;
		$this->env  	 = $context->env;
		$this->config 	 = $context->config;
	}

	/**
	 * @return void
	 * @throws InvalidSession
	 */
	public function initialize():void
	{
		$this->services->session->start();
		$this->config->configureResponder($this->responder);
		$this->responder->application = $this->application;
		$this->responder->services = $this->services;
		$this->responder->environment = $this->env;
	}
}
