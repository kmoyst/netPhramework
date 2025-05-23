<?php

namespace netPhramework\responding;

use netPhramework\rendering\Viewable;

readonly class DisplayableResponse implements Response
{
	public function __construct(private Viewable $content, private ResponseCode $code) {}

	public function deliver(Responder $responder): void
	{
		$responder->display($this->content, $this->code);
	}

}