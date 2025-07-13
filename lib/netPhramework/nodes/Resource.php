<?php

namespace netPhramework\nodes;

use netPhramework\common\Utils;
use netPhramework\exceptions\NodeNotFound;
use Stringable;
abstract class Resource implements Node, Stringable
{
	private bool $isIndex = false;

	public function getChild(string $id): never
	{
		throw new NodeNotFound("Not Found: $id");
	}

	public function getResourceId(): string
	{
		return $this->isIndex ? '' : $this->getName();
	}

	public function asIndex():self
	{
		$this->isIndex = true;
		return $this;
	}

	public function getName():string
	{
		return Utils::camelToKebab(Utils::baseClassName($this));
	}

	public function __toString():string
	{
		return $this->getResourceId();
	}
}