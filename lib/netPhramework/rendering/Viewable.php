<?php

namespace netPhramework\rendering;

use netPhramework\responding\Relayer;
use netPhramework\responding\Responder;
use netPhramework\responding\ResponseContent;

abstract class Viewable implements Encodable, ResponseContent
{
	public function encode(Encoder $encoder): string
	{
		return $encoder->encodeViewable($this);
	}

	public function chooseRelay(Responder $responder): Relayer
	{
		return $responder->getDisplayer();
	}

	abstract public function getTemplateName():string;
	abstract public function getVariables():iterable;
}