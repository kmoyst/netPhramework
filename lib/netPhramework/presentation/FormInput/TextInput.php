<?php

namespace netPhramework\presentation\FormInput;

use netPhramework\common\Utils;
use netPhramework\rendering\Encodable;

class TextInput extends Input
{
	protected string $templateName = 'form/text-input';
	protected ?string $value;

	public function setValue(string|Encodable|null $value): Input
	{
		$this->value = $value;
		return $this;
	}

	public function getVariables(): iterable
	{
		parent::getVariables();
		$v = $this->variables;
		$v->add('name', $this->name);
		$v->add('label', Utils::kebabToSpace($this->name));
		$v->add('id', $this->name);
		$v->add('value', $this->value ?? '');
		$v->add('type', 'text');
		return $v;
	}
}