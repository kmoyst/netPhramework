<?php

namespace netPhramework\responding;

class Display implements Response
{
	private DisplayableContent $displayable;
	private ResponseCode $responseCode;

	/**
	 * @param DisplayableContent $displayable
	 * @param ResponseCode $responseCode
	 */
	public function __construct(DisplayableContent $displayable, ResponseCode $responseCode)
	{
		$this->displayable = $displayable;
		$this->responseCode = $responseCode;
	}

	public function deliver(Responder $responder): void
	{
		$this->displayable->relay($responder, $this->responseCode);
	}
}