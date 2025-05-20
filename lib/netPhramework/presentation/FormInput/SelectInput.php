<?php

namespace netPhramework\presentation\FormInput;

class SelectInput extends Input
{
	protected ?string $value;
	protected string $id;

	public function __construct(string $name, protected iterable $options)
	{
		parent::__construct($name);
	}

	public function setId(string $id): SelectInput
	{
		$this->id = $id;
		return $this;
	}

	public function setValue(?string $value): SelectInput
	{
		$this->value = $value;
		return $this;
	}

	public function getTemplateName(): string
	{
		return 'form/select-input';
	}

	public function getVariables(): array
	{
		return [
			'name' => $this->name,
			'options' => $this->options,
			'id' => $this->id ?? $this->name,
			'selectedValue' => $this->value ?? ''
		];
	}

}