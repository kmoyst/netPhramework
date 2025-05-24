<?php

namespace netPhramework\dispatching;

use netPhramework\rendering\Encodable;
use netPhramework\rendering\Encoder;

/**
 * The most basic ReadableLocation interface. Provides ReadablePath and iterable
 * Parameters.
 *
 */
abstract class ReadableLocation implements Encodable
{
	public function encode(Encoder $encoder):string
	{
		return $encoder->encodeLocation($this);
	}

	/**
	 * Returns a readable Path.
	 *
	 * @return ReadablePath
	 */
    abstract public function getPath():ReadablePath;

	/**
	 * Returns parameters for iteration.
	 *
	 * @return iterable
	 */
    abstract public function getParameters():iterable;
}