<?php

namespace netPhramework\presentation\FormInput;

use netPhramework\dispatching\Location;

class HiddenInput extends Input
{
	public function __construct(
		string $name,
		private string|Location|null $value = null
	)
	{
		parent::__construct($name);
	}

	public function setValue(Location|string|null $value): HiddenInput
	{
		$this->value = $value;
		return $this;
	}

	public function getTemplateName(): string
	{
		return 'form/hidden-input';
	}

	public function getVariables(): iterable
	{
		return [
			'name' => $this->name,
			'value' => $this->value ?? ''
		];
	}
}