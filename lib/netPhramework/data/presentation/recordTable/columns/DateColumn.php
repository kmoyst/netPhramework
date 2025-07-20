<?php

namespace netPhramework\data\presentation\recordTable\columns;

use DateMalformedStringException;
use DateTime;
use netPhramework\data\core\Record;

class DateColumn extends TextColumn
{
	public function __construct(
		string $name,
		private readonly string $format = 'Y-m-d',
		int $width = 100,
		?string $header = null,
	)
	{
		parent::__construct($name, $width, $header);
	}

	public function getSortableValue(Record $record): string
	{
		try {
			$dt = new DateTime($record->getValue($this->getName()) ?? '');
			return $dt->format($this->format);
		} catch (DateMalformedStringException) {
			return $record->getValue($this->getName());
		}
	}
}