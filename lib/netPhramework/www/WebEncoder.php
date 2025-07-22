<?php

namespace netPhramework\www;

use netPhramework\rendering\Encoder;

class WebEncoder extends Encoder
{
	public function encodeText(string $text): string
	{
		return htmlspecialchars($text);
	}
}