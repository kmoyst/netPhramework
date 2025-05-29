<?php

namespace netPhramework\responding;

use netPhramework\locating\MutableLocation;
use netPhramework\locating\Redirectable;

class Redirection extends MutableLocation implements Redirectable, Response
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