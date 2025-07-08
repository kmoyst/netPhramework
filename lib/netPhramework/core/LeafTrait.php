<?php

namespace netPhramework\core;

use netPhramework\common\Utils;
use netPhramework\exceptions\NodeNotFound;

trait LeafTrait
{
	protected bool $isDefault = false;

	public function getChild(string $name): never
	{
		throw new NodeNotFound("Not Found: $name");
	}

	public function getNodeId(): string
	{
		return $this->isDefault ? '' : $this->getName();
	}

	public function makeDefault():self
	{
		$this->isDefault = true;
		return $this;
	}

	public function getName():string
	{
		return Utils::camelToKebab(Utils::baseClassName($this));
	}
}