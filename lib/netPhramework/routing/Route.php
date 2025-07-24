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

	public function equals(Route $route):bool
	{
		if($this->getName() !== $route->getName())
			return false;
		elseif($this->getNext() === null && $route->getNext() !== null)
			return false;
		elseif($this->getNext() !== null && $route->getNext() === null)
			return false;
		elseif($this->getNext() === null && $route->getNext() === null)
			return true;
		else
			return $this->getNext()->equals($route->getNext());
	}

	abstract public function getName():?string;
    abstract public function getNext():?self;
}