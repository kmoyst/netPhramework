<?php

namespace netPhramework\responding;

use netPhramework\common\Variables;
use netPhramework\locating\MutableLocation;
use netPhramework\locating\MutablePath;
use netPhramework\locating\Redirectable;

class Redirection extends MutableLocation implements Redirectable, Response
{
	private ResponseCode $code;
	private Variables $parameters;

	public function __construct(private readonly MutablePath $path)
	{
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