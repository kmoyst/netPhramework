<?php

namespace netPhramework\core;

use netPhramework\common\Utils;
use netPhramework\exceptions\NodeNotFound;

trait LeafTrait
{
	protected string $name;

	public function getChild(string $name): never
	{
		throw new NodeNotFound("Not Found: $name");
	}

	public function getName(): string
	{
		return $this->name ?? Utils::camelToKebab(Utils::baseClassName($this));
	}
}