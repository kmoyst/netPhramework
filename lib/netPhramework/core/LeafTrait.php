<?php

namespace netPhramework\core;

use netPhramework\common\Utils;
use netPhramework\exceptions\NodeNotFound;

trait LeafTrait
{
	protected bool $isIndex = false;

	public function getChild(string $name): never
	{
		throw new NodeNotFound("Not Found: $name");
	}

	public function getNodeId(): string
	{
		return $this->isIndex ? '' : $this->getName();
	}

	public function makeIndex():self
	{
		$this->isIndex = true;
		return $this;
	}

	public function getName():string
	{
		return Utils::camelToKebab(Utils::baseClassName($this));
	}
}