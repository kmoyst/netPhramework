<?php

namespace netPhramework\presentation\FormInput;

use netPhramework\rendering\Viewable;

abstract class Input implements Viewable
{
	public function __construct(protected readonly string $name) {}

	public function getName():string
	{
		return $this->name;
	}
}