<?php

namespace netPhramework\presentation\FormInput;

use netPhramework\rendering\Encodable;
use netPhramework\rendering\Viewable;

abstract class Input extends Viewable
{
	public function __construct(protected readonly string $name) {}

	public function getName():string
	{
		return $this->name;
	}

	abstract public function setValue(Encodable|string|null $value):self;
}