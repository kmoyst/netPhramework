<?php

namespace netPhramework\presentation;

use netPhramework\common\Utils;
use netPhramework\common\Variables;
use netPhramework\rendering\Encodable;

class TextInput extends Input
{
	protected string $templateName = 'form/text-input';
	protected ?string $value;
	protected string $label;

	public function setValue(string|Encodable|null $value): Input
	{
		$this->value = $value;
		return $this;
	}

	public function setLabel(string $label): self
	{
		$this->label = $label;
		return $this;
	}

	public function getVariables(): Variables
	{
		return parent::getVariables()
			->add('value', $this->value ?? '')
			->add('type', 'text')
			->add('label', Utils::kebabToSpace($this->name))
			->add('id', $this->name)
		;
	}
}