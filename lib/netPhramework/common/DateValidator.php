<?php

namespace netPhramework\common;

use DateMalformedStringException;
use DateTime;

final class DateValidator implements Validator
{
	public function validate(?string $value): bool
	{
		try {
			new DateTime($value);
			return true;
		} catch (DateMalformedStringException) {
			return false;
		}
	}
}