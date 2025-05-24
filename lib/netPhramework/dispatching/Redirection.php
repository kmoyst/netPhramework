<?php

namespace netPhramework\dispatching;

use netPhramework\responding\Response;
use netPhramework\responding\ResponseCode;
use netPhramework\responding\ResponseFactory;

class Redirection extends Location
	implements Redirectable, ResponseFactory
{
	private ResponseCode $code;

	public function setResponseCode(ResponseCode $code): Redirectable
	{
		$this->code = $code;
		return $this;
	}

	public function getResponse():Response
	{
		return new Response()
			->setContent($this)
			->setCode($this->code)
			;
	}
}