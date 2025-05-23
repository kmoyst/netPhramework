<?php

namespace netPhramework\rendering;

use netPhramework\responding\Responder;
use netPhramework\responding\Response;
use netPhramework\responding\ResponseCode;

readonly class Display implements Response
{
	public function __construct(private Viewable $content, private ResponseCode $code) {}

	public function deliver(Responder $responder): void
	{
		$responder->display($this->content, $this->code);
	}

}