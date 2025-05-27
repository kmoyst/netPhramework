<?php

namespace netPhramework\db\core;

use netPhramework\common\Utils;
use netPhramework\core\Component;
use netPhramework\exceptions\NotFound;

abstract class RecordSetProcess extends RecordSetNode
{
	public function __construct(protected readonly ?string $name = null) {}

	public function getName(): string
	{
		return $this->name ?? Utils::camelToKebab(Utils::baseClassName($this));
	}

	public function getChild(string $name): Component
	{
		throw new NotFound("Not Found: $name");
	}
}