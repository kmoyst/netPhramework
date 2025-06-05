<?php

namespace netPhramework\locating;

use netPhramework\rendering\Encodable;
use netPhramework\rendering\Encoder;
use Stringable;

/**
 * Basic MutablePath interface. Can be read / traversed, but not modified.
 *
 */
abstract class Path implements Encodable
{
	public function encode(Encoder $encoder): string|Stringable
	{
		return $encoder->encodePath($this);
	}

	abstract public function getName():?string;
    abstract public function getNext():?Path;
}