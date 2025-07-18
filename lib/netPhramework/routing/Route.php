<?php

namespace netPhramework\routing;

use netPhramework\rendering\Encodable;
use netPhramework\rendering\Encoder;
use Stringable;

abstract class Route implements Encodable
{
	public function encode(Encoder $encoder): string|Stringable
	{
		return $encoder->encodePath($this);
	}

	abstract public function getName():?string;
    abstract public function getNext():?self;
}