<?php

namespace netPhramework\responding;

use netPhramework\dispatching\Dispatchable;
use netPhramework\dispatching\MutableLocation;

class Redirection extends MutableLocation implements Dispatchable, Response
{
	private ResponseCode $responseCode;

	public function deliver(Responder $responder): void
	{
        new RedirectableContent($this)->relay($responder, $this->responseCode);
	}

	/** @inheritDoc */
	public function seeOther(): self
	{
		$this->redirect(ResponseCode::SEE_OTHER);
		return $this;
	}

	/**
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