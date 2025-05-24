<?php

namespace netPhramework\rendering;

abstract class Viewable implements Encodable
{
	public function encode(Encoder $encoder): string
	{
		return $encoder->encodeViewable($this);
	}

	abstract public function getTemplateName():string;
	abstract public function getVariables():iterable;
}