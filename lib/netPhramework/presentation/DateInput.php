<?php

namespace netPhramework\presentation;

use DateMalformedStringException;
use DateTime;
use netPhramework\data\exceptions\InvalidValue;
use netPhramework\rendering\Encodable;

class DateInput extends TextInput
{
	protected string $templateName = 'form/date-input';

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