<?php

namespace netPhramework\presentation\FormInput;

use netPhramework\common\Variables;
use netPhramework\rendering\Encodable;

class SelectInput extends Input
{
	protected string $templateName = 'form/select-input';
	protected ?string $value;
	protected string $id;

	public function __construct(string $name,
								protected iterable $options = [])
	{
		parent::__construct($name);
	}

	public function setOptions(iterable $options): self
	{
		$this->options = $options;
		return $this;
	}

	public function setId(string $id): self
	{
		$this->id = $id;
		return $this;
	}

	public function setValue(string|Encodable|null $value): self
	{
		$this->value = $value;
		return $this;
	}

	public function getVariables(): Variables
	{
		return parent::getVariables()
			->add('options', $this->options)
			->add('id', $this->id ?? $this->name)
			->add('selectedValue', $this->value ?? '')
			;
	}
}