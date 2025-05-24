<?php

namespace netPhramework\dispatching;

use netPhramework\responding\Responder;
use netPhramework\responding\ResponseCode;
use netPhramework\responding\Response;

class Redirection extends Location implements Redirectable, Response
{
	private ResponseCode $code;

	public function setResponseCode(ResponseCode $code): Redirectable
	{
		$this->code = $code;
		return $this;
	}

	public function deliver(Responder $responder): void
	{
		$responder->redirect($this, $this->code);
	}
}