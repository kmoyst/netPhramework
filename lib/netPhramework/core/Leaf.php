<?php

namespace netPhramework\core;

use netPhramework\common\Utils;
use netPhramework\exceptions\ResourceNotFound;

abstract class Leaf implements Resource
{
	private bool $isIndex = false;

	public function getChild(string $id): never
	{
		throw new ResourceNotFound("Not Found: $id");
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
}