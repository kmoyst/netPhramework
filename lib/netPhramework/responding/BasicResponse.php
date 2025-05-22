<?php

namespace netPhramework\responding;

readonly class BasicResponse implements Response
{
    /**
	 * @param ResponseContent $responseContent
	 * @param ResponseCode $responseCode
	 */
	public function __construct(private ResponseContent $responseContent,
                                private ResponseCode $responseCode) {}

	public function deliver(Responder $responder): void
	{
		$this->responseContent->relay($responder, $this->responseCode);
	}
}