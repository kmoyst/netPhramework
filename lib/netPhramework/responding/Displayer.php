<?php

namespace netPhramework\responding;

use netPhramework\rendering\Encodable;
use netPhramework\rendering\Encoder;

readonly class Displayer implements Relayer
{
	public function __construct(private Encoder $encoder) {}

	public function relay(Encodable $content, ResponseCode $code): void
	{
		http_response_code($code->value);
		echo $content->encode($this->encoder);
	}
}