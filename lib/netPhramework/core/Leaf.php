<?php

namespace netPhramework\core;

use netPhramework\common\Utils;
use netPhramework\exceptions\NodeNotFound;

abstract class Leaf implements Node
{
	private bool $isIndex = false;

	public function getChild(string $id): never
	{
		throw new NodeNotFound("Not Found: $id");
	}

	public function getNodeId(): string
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
}