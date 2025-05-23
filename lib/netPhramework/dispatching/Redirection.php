<?php

namespace netPhramework\dispatching;

use netPhramework\dispatching\interfaces\DispatchableLocation;
use netPhramework\responding\Responder;
use netPhramework\responding\Response;
use netPhramework\responding\ResponseCode;

class Redirection
	extends MutableLocation implements DispatchableLocation, Response
{
	private ResponseCode $responseCode;

	public function deliver(Responder $responder): void
	{
		$responder->redirect($this, $this->responseCode);
	}

	/**
	 * To set Response to redirectable with explicit code.
	 * Finalizes the dispatch based on current Path and Parameters.
	 *
	 * @param ResponseCode $code
	 * @return $this
	 */
	public function setResponseCode(ResponseCode $code):self
	{
		$this->responseCode = $code;
		return $this;
	}
}