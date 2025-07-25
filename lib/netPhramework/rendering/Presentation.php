<?php

namespace netPhramework\rendering;

use netPhramework\exchange\Responder;
use netPhramework\exchange\Response;
use netPhramework\exchange\ResponseCode;

readonly class Presentation implements Response
{
	public function __construct
	(
		private Wrappable $content,
		private ResponseCode $code
	)
	{}

	public function deliver(Responder $responder): void
	{
		$responder->present($this->content, $this->code);
	}
}