<?php

namespace netPhramework\presentation\FormInput;

use DateMalformedStringException;
use DateTime;
use netPhramework\common\Utils;
use netPhramework\common\Variables;
use netPhramework\db\exceptions\InvalidValue;

class DateInput extends Input
{
	private string $value;

	public function getTemplateName(): string
	{
		return 'form/date-input';
	}

	public function getVariables(): iterable
	{
		$v = new Variables();
		$v->add('name', $this->name);
		$v->add('label', Utils::kebabToSpace($this->name));
		$v->add('id', $this->name);
		$v->add('value', $this->value);
		return $v;
	}

	/**
	 * @param string|null $value
	 * @return Input
	 * @throws InvalidValue
	 */
	public function setValue(?string $value): Input
	{
		try {
			$date = new DateTime($value);
			$this->value = $date->format('Y-n-j');
		} catch (DateMalformedStringException) {
			throw new InvalidValue("Invalid value: $value");
		}
		return $this;
	}
}