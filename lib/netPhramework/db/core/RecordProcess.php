<?php

namespace netPhramework\db\core;

use netPhramework\common\Utils;
use netPhramework\exceptions\NotFound;

abstract class RecordProcess extends RecordNode
{
	public function __construct(protected readonly ?string $name = null) {}

	public function getName(): string
	{
		return $this->name ?? Utils::camelToKebab(Utils::baseClassName($this));
	}

	public function getChild(string $name): never
	{
		throw new NotFound("Not Found: $name");
	}
}