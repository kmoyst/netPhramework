<?php

namespace netPhramework\core;

use netPhramework\common\Utils;
use netPhramework\exceptions\ComponentNotFound;

trait LeafTrait
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

	/**
	 * Check if node is a composite
	 *
	 * @return bool
	 */
	public function isComposite():bool
	{
		return false;
	}

	/**
	 * Test if node is a Leaf
	 *
	 * @return bool
	 */
	public function isLeaf():bool
	{
		return true;
	}
}