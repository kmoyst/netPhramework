<?php

namespace netPhramework\responding;

use netPhramework\dispatching\Dispatchable;
use netPhramework\dispatching\MutableLocation;

class Redirection extends MutableLocation implements Dispatchable, Response
{
	private ResponseContent $responseContent;
	private ResponseCode $responseCode;

	public function deliver(Responder $responder): void
	{
		$this->responseContent->relay($responder, $this->responseCode);
	}

	/** @inheritDoc */
	public function seeOther(): self
	{
		$this->redirect(ResponseCode::SEE_OTHER);
		return $this;
	}

	/**
	 * Finalizes a redirect Response with specified ResponseCode.
	 * Uses the current state of Path and Parameters (Location) configured
	 * through direct interaction with this object's Path and Parameters.
	 *
	 * @param ResponseCode $code
	 * @return $this
	 */
	public function redirect(ResponseCode $code):self
	{
		$this->responseContent  = new RedirectableContent($this);
		$this->responseCode     = $code;
		return $this;
	}
}