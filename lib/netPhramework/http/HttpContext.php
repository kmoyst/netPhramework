<?php

namespace netPhramework\http;

use netPhramework\core\Context;
use netPhramework\core\Environment;
use netPhramework\exchange\Request;
use netPhramework\exchange\Responder;
use netPhramework\exchange\Services;

class HttpContext implements Context
{
	protected(set) Request $request {get{
		if(!isset($this->request))
			$this->request = new HttpRequest();
		return $this->request;
	}set{}}

	protected(set) Responder $responder{get{
		if(!isset($this->responder))
			$this->responder = new HttpResponder();
		return $this->responder;
	}set{}}

	protected(set) Services $services{get{
		if(!isset($this->services))
			$this->services = new HttpServices();
		return $this->services;
	}set{}}

	protected(set) Environment $environment {get{
		if(!isset($this->environment))
			$this->environment = new HttpEnvironment();
		return $this->environment;
	}set{}}

	public function configure(): void
	{
		$this->responder->templateFinder
			->directory('../templates')
			->directory('../html')
			->directory(__DIR__ . '/../../../templates')
			->extension('tpl')
			->extension('phtml')
			->extension('css')
		;

	}


}