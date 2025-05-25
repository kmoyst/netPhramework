<?php

namespace netPhramework\presentation\FormInput;

use netPhramework\common\Utils;

class SelectInputWithLabel extends SelectInput
{
	protected string $templateName = 'select-input-with-label';
	protected string $label;

	public function setLabel(string $label): self
	{
		$this->label = $label;
		return $this;
	}

	public function getVariables(): iterable
	{
		parent::getVariables();
		return $this->variables
			->add('label', $this->label ?? Utils::kebabToSpace($this->name));
	}
}