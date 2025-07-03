<?php

namespace netPhramework\locating;

use netPhramework\common\Variables;

readonly class VariablesFromUri
{
	public function __construct(private string $uri) {}

	public function parse():Variables
	{
		$variables = new Variables();
		if(preg_match('|\?(.+)$|', $this->uri, $matches))
		{
			parse_str($matches[1], $arr);
			$variables->merge($arr);
		}
		return $variables;
	}
}