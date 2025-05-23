<?php

namespace netPhramework\core;

use netPhramework\common\Utils;
use netPhramework\exceptions\ComponentNotFound;

abstract class Leaf implements Component
{
	public function __construct(protected readonly ?string $name = null) {}

	public function getChild(string $name): never
	{
		throw new ComponentNotFound("Not Found: $name");
	}

	public function getName(): string
	{
		return $this->name ?? Utils::camelToKebab(Utils::baseClassName($this));
	}
}