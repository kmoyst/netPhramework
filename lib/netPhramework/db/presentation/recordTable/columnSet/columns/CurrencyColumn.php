<?php

namespace netPhramework\db\presentation\recordTable\columnSet\columns;

use netPhramework\db\mapping\Record;
use netPhramework\rendering\Encodable;

class CurrencyColumn extends TextColumn
{
	public function getEncodableValue(Record $record): Encodable|string
	{
		return '$'.number_format(parent::getEncodableValue($record),2);
	}
}