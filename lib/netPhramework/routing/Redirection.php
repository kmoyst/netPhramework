<?php

namespace netPhramework\routing;

use netPhramework\common\Variables;
use netPhramework\exchange\Responder;
use netPhramework\exchange\Response;
use netPhramework\exchange\ResponseCode;

class Redirection extends Location implements Redirectable, Response
{
	private ResponseCode $code;

	public function __construct(MutablePath $path)
	{
		$this->path = $path;
		$this->parameters = new Variables();
	}

	public function setResponseCode(ResponseCode $code): Redirectable
	{
		$this->code = $code;
		return $this;
	}

	public function deliver(Responder $responder): void
	{
		$responder->redirect($this, $this->code);
	}

	public function getPath(): MutablePath
	{
		return $this->path;
	}

	public function getParameters(): Variables
	{
		return $this->parameters;
	}
}