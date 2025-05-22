<?php

namespace netPhramework\core;

use netPhramework\common\Utils;
use netPhramework\exceptions\ComponentNotFound;
use netPhramework\exceptions\Exception;

abstract class Leaf implements Component
{
	public function __construct(protected readonly ?string $name = null) {}

	public function getChild(string $name): never
	{
		throw new ComponentNotFound("Not Found: $name");
	}

	/**
	 *
	 * @param Exchange $exchange
	 * @return void
	 * @throws Exception
	 */
	abstract public function handleExchange(Exchange $exchange): void;

	public function getName(): string
	{
		return $this->name ?? Utils::camelToKebab(Utils::baseClassName($this));
	}
}