<?php

namespace netPhramework\core;

use netPhramework\rendering\Encodable;
use netPhramework\rendering\Encoder;

readonly class Responder
{
	public function __construct(private Encoder $encoder) {}

	public function present(Encodable $content, ResponseCode $code): void
	{
		http_response_code($code->value);
		echo $content->encode($this->encoder);
	}

	public function redirect(Encodable $content, ResponseCode $code): void
	{
		http_response_code($code->value);
		header("MutableLocation: " . $content->encode($this->encoder));
	}
}