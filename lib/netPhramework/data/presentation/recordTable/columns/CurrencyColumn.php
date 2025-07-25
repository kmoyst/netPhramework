<?php

namespace netPhramework\data\presentation\recordTable\columns;

use netPhramework\data\core\Record;
use netPhramework\rendering\Encodable;

class CurrencyColumn extends TextColumn
{
	public function getOperableValue(Record $record): string
	{
		return parent::getOperableValue($record) / 100;
	}

	public function getEncodableValue(Record $record): Encodable|string
	{
		return '$'.number_format($this->getOperableValue($record), 2);
	}
}