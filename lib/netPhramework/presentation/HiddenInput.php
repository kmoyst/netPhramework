<?php

namespace netPhramework\presentation;

use netPhramework\common\Variables;
use netPhramework\rendering\Encodable;

class HiddenInput extends Input
{
	protected string $templateName = 'form/hidden-input';

	public function __construct(
		string $name,
		private string|Encodable|null $value = null
	)
	{
		parent::__construct($name);
	}

	public function setValue(Encodable|string|null $value): HiddenInput
	{
		$this->value = $value;
		return $this;
	}

	public function getVariables(): Variables
	{
		return $this->variables
			->add('name', $this->name)
			->add('value', $this->value ?? '')
		;
	}
}