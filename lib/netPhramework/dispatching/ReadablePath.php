<?php

namespace netPhramework\dispatching;

use netPhramework\rendering\Encodable;
use netPhramework\rendering\Encoder;
use netPhramework\responding\Relayer;
use netPhramework\responding\Responder;
use netPhramework\responding\ResponseContent;

/**
 * Basic Path interface. Can be read / traversed, but not modified.
 *
 */
abstract class ReadablePath implements ResponseContent
{
	public function encode(Encoder $encoder): string
	{
		return $encoder->encodePath($this);
	}

	public function chooseRelay(Responder $responder): Relayer
	{
		return $responder->getRedirector();
	}

	abstract public function getName():?string;
    abstract public function getNext():?ReadablePath;
}