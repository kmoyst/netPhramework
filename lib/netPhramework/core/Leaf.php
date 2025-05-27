<?php

namespace netPhramework\core;

use netPhramework\common\Utils;
use netPhramework\exceptions\ComponentNotFound;

trait Leaf
{
	protected ?string $name = null;

	/**
	 * @param string $name
	 * @return never
	 * @throws ComponentNotFound
	 */
	public function getChild(string $name): never
	{
		throw new ComponentNotFound("Not Found: $name");
	}

	public function getName(): string
	{
		return $this->name ?? Utils::camelToKebab(Utils::baseClassName($this));
	}
}