<?php

namespace netPhramework\db\core;

use netPhramework\common\Utils;

abstract class Process
{
	public function __construct(protected ?string $name = null) {}

	public function getName():string {
		return $this->name ??
			Utils::camelToKebab(Utils::baseClassName(get_class($this)));
	}
}