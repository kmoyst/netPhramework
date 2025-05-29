<?php

namespace netPhramework\locating;

use netPhramework\rendering\Encodable;
use netPhramework\rendering\Encoder;

/**
 * Basic MutablePath interface. Can be read / traversed, but not modified.
 *
 */
abstract class Path implements Encodable
{
	public function encode(Encoder $encoder): string
	{
		return $encoder->encodePath($this);
	}

	abstract public function getName():?string;
    abstract public function getNext():?Path;
}