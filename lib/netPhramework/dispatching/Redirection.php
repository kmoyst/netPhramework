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
	 * Convenience method to set common Response code for redirect method.
	 * Finalizes the dispatch based on current Path and Parameters.
	 *
	 * @return $this
	 */
	public function seeOther(): self
	{
		$this->redirect(ResponseCode::SEE_OTHER);
		return $this;
	}

	/**
	 * To set Response to redirectable with explicit code.
	 * Finalizes the dispatch based on current Path and Parameters.
	 *
	 * @param ResponseCode $code
	 * @return $this
	 */
	public function redirect(ResponseCode $code):self
	{
		$this->responseCode = $code;
		return $this;
	}
}