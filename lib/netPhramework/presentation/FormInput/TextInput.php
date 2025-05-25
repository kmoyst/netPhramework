<?php

namespace netPhramework\presentation\FormInput;

use netPhramework\common\Utils;
use netPhramework\common\Variables;
use netPhramework\rendering\Encodable;

class TextInput extends Input
{
	protected ?string $value;

	public function setValue(string|Encodable|null $value): Input
	{
		$this->value = $value;
		return $this;
	}

	public function getTemplateName(): string
	{
		return 'form/text-input';
	}

	public function getVariables(): Variables
	{
		$v = new Variables();
		$v->add('name', $this->name);
		$v->add('label', Utils::kebabToSpace($this->name));
		$v->add('id', $this->name);
		$v->add('value', $this->value ?? '');
		$v->add('type', 'text');
		return $v;
	}
}