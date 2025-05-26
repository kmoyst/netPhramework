<?php

namespace netPhramework\rendering;

readonly class Template implements Encodable
{
	public function __construct(private string $template) {}

	public function encode(Encoder $encoder): string
	{
		return $encoder->encodeTemplate($this->template);
	}
}