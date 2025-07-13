<?php

namespace netPhramework\rendering;

class HtmlEncoder extends Encoder
{
	public function encodeText(string $text): string
	{
		return htmlspecialchars($text);
	}
}