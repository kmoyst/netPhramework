<?php

namespace netPhramework\dispatching;

use netPhramework\responding\Responder;
use netPhramework\responding\Response;
use netPhramework\responding\ResponseCode;

class DispatchableLocation extends MutableLocation implements Dispatchable, Response
{
	private ResponseCode $responseCode;

	public function deliver(Responder $responder): void
	{
		$responder->redirect($this, $this->responseCode);
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