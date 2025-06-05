<?php

namespace netPhramework\rendering;

use Stringable;

readonly class Template implements Encodable
{
	public function __construct(private string $template) {}

	public function encode(Encoder $encoder): string|Stringable
	{
		return $encoder->encodeTemplate($this->template);
	}
}