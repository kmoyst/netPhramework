<?php

namespace netPhramework\http;

use netPhramework\core\Context;
use netPhramework\exchange\Request;

class HttpContext implements Context
{
	private(set) Request $request {get{
		if(!isset($this->request))
			$this->request = new HttpRequest();
	}set{}}

}