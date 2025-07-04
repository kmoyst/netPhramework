<?php

namespace netPhramework\responding;

use netPhramework\rendering\Encodable;

class Presentation implements Response
{
	private Encodable $content;
	private ResponseCode $code;

	public function setContent(Encodable $content): self
	{
		$this->content = $content;
		return $this;
	}

	public function setCode(ResponseCode $code): self
	{
		$this->code = $code;
		return $this;
	}

	public function deliver(Responder $responder): void
	{
		$responder->present($this->content, $this->code);
	}
}