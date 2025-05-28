<?php

namespace netPhramework\presentation\FormInput;

use netPhramework\common\Utils;
use netPhramework\common\Variables;
use netPhramework\db\exceptions\InvalidValue;
use netPhramework\rendering\Encodable;

class CheckboxInput extends Input
{
	protected string $templateName = 'form/checkbox-input';
	protected int $value;
	protected ?string $label;

	/**
	 * @param Encodable|string|null $value
	 * @return Input
	 * @throws InvalidValue
	 */
	public function setValue(Encodable|string|null $value): Input
	{
		if(is_null($value))
			$this->value = 0;
		elseif(is_numeric($value) && $value < 2)
		{
			$this->value = (int)$value;
		}
		else
		{
			throw new InvalidValue("Invalid Value for $this->name: $value");
		}
		return $this;
	}

	public function setLabel(?string $label): self
	{
		$this->label = $label;
		return $this;
	}

	public function getVariables(): Variables
	{
		return parent::getVariables()
			->add('value', $this->value ?? 0)
			->add('checked', empty($this->value) ? '' : 'checked')
			->add('label', $this->label ?? Utils::kebabToSpace($this->name))
			->add('id', $this->name)
			;
	}
}