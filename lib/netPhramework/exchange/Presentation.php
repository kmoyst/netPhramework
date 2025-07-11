<?php

namespace netPhramework\exchange;

use netPhramework\rendering\Wrappable;

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