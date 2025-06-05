<?php

namespace netPhramework\rendering;

use Stringable;

abstract class Viewable implements Encodable
{
	public function encode(Encoder $encoder): string|Stringable
	{
		return $encoder->encodeViewable($this);
	}

	abstract public function getTemplateName():string;
	abstract public function getVariables():iterable;
}