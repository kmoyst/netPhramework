<?php

namespace netPhramework\routing;

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
	 * Returns a readable Path.
	 *
	 * @return Route
	 */
    abstract public function getPath():Route;

	/**
	 * Returns parameters for iteration.
	 *
	 * @return iterable
	 */
    abstract public function getParameters():iterable;
}