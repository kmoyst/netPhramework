<?php

namespace netPhramework\locating;

use netPhramework\rendering\Encodable;
use netPhramework\rendering\Encoder;
use Stringable;

/**
 * The most basic Location interface. Provides Path and iterable
 * Parameters.
 *
 */
abstract class ReadableLocation implements Encodable
{
	public function encode(Encoder $encoder):string|Stringable
	{
		return $encoder->encodeLocation($this);
	}

	/**
	 * Returns a readable MutablePath.
	 *
	 * @return Path
	 */
    abstract public function getPath():Path;

	/**
	 * Returns parameters for iteration.
	 *
	 * @return iterable
	 */
    abstract public function getParameters():iterable;
}