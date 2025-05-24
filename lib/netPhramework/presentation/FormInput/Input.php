<?php

namespace netPhramework\presentation\FormInput;

use netPhramework\dispatching\interfaces\ReadableLocation;
use netPhramework\rendering\Viewable;

abstract class Input implements Viewable
{
	public function __construct(protected readonly string $name) {}

	public function getName():string
	{
		return $this->name;
	}

	abstract public function setValue(ReadableLocation|string|null $value):self;
}