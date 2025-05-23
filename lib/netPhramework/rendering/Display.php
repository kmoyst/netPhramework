<?php

namespace netPhramework\rendering;

use netPhramework\core\Responder;
use netPhramework\core\Response;
use netPhramework\core\ResponseCode;

readonly class Display implements Response
{
	public function __construct(private Viewable $content,
								private ResponseCode $code) {}

	public function deliver(Responder $responder): void
	{
		$responder->display($this->content, $this->code);
	}
}