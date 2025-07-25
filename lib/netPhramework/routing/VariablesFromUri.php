<?php

namespace netPhramework\routing;

use netPhramework\common\Variables;

class VariablesFromUri extends Variables
{
	public function __construct(private string $uri) {}

	public function init():self
	{
		if(preg_match('|\?(.+)$|', $this->uri, $matches))
		{
			parse_str($matches[1], $arr);
			$this->merge($arr);
		}
		return $this;
	}
}