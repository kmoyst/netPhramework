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

	public function getLastName():?string
	{
		if($this->getNext() === null)
			return $this->getName();
		else
			return $this->getNext()->getLastName();
	}

	public function getPenultimateName():?string
	{
		if($this->getNext() === null || $this->getNext()->getNext() === null)
			return $this->getName();
		else
			return $this->getNext()->getPenultimateName();
	}

	abstract public function getName():?string;
    abstract public function getNext():?self;
}