<?php

namespace netPhramework\dispatching;

use netPhramework\rendering\Encodable;
use netPhramework\rendering\Encoder;

/**
 * Basic Path interface. Can be read / traversed, but not modified.
 *
 */
abstract class ReadablePath implements Encodable
{
	public function encode(Encoder $encoder): string
	{
		return $encoder->encodePath($this);
	}

	abstract public function getName():?string;
    abstract public function getNext():?ReadablePath;
}