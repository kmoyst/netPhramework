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

	public function getName(): string
	{
		$name = Utils::camelToKebab(Utils::baseClassName($this));
		return $this->resolveName($name);
	}

	public function makeDefault():self
	{
		$this->isDefault = true;
		return $this;
	}

	protected function resolveName(string $name):string
	{
		return $this->isDefault ? '' : $name;
	}
}