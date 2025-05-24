<?php

namespace netPhramework\dispatching;

use netPhramework\rendering\Encoder;
use netPhramework\responding\Relayer;
use netPhramework\responding\Responder;
use netPhramework\responding\ResponseContent;

/**
 * The most basic ReadableLocation interface. Provides ReadablePath and iterable
 * Parameters.
 *
 */
abstract class ReadableLocation implements ResponseContent
{
	public function encode(Encoder $encoder):string
	{
		return $encoder->encodeLocation($this);
	}

	public function chooseRelay(Responder $responder): Relayer
	{
		return $responder->getRedirector();
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