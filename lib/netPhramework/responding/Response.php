<?php

namespace netPhramework\responding;

class Response
{
	private ResponseContent $content;
	private ResponseCode $code;

	public function setContent(ResponseContent $content): self
	{
		$this->content = $content;
		return $this;
	}

	public function setCode(ResponseCode $code): self
	{
		$this->code = $code;
		return $this;
	}

	public function deliver(Responder $responder):void
	{
		$this->content->chooseRelay($responder)
			->relay($this->content, $this->code);
	}
}