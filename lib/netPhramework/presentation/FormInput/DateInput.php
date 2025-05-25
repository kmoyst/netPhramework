<?php

namespace netPhramework\presentation\FormInput;

use DateMalformedStringException;
use DateTime;
use netPhramework\common\Utils;
use netPhramework\db\exceptions\InvalidValue;
use netPhramework\rendering\Encodable;

class DateInput extends Input
{
	protected string $templateName = 'form/date-input';
	private string $value;

	public function getVariables(): iterable
	{
		parent::getVariables();
		$v = $this->variables;
		$v->add('name', $this->name);
		$v->add('label', Utils::kebabToSpace($this->name));
		$v->add('id', $this->name);
		$v->add('value', $this->value);
		return $v;
	}


	/**
	 * @param Encodable|string|null $value
	 * @return Input
	 * @throws InvalidValue
	 */
	public function setValue(Encodable|string|null $value): Input
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