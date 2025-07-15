<?php

namespace netPhramework\http;

use netPhramework\core\Context;
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
}