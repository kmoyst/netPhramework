<?php

namespace netPhramework\presentation\FormInput;

use netPhramework\common\Utils;

class SelectInputWithLabel extends SelectInput
{
	protected string $label;

	public function setLabel(string $label): SelectInputWithLabel
	{
		$this->label = $label;
		return $this;
	}

	public function getTemplateName(): string
	{
		return 'select-input-with-label';
	}

	public function getVariables(): array
	{
		$v = parent::getVariables();
		$v['label'] = $this->label ?? Utils::kebabToSpace($this->name);
		return $v;
	}
}