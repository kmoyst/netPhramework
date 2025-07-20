<?php

namespace netPhramework\data\presentation\recordTable\columnSet;

use netPhramework\data\mapping\Field;
use netPhramework\data\mapping\FieldType;
use netPhramework\data\presentation\recordTable\columns\DateColumn;
use netPhramework\data\presentation\recordTable\columns\TextColumn;

readonly class ColumnMapper
{
	public function mapColumn(Field $field):Column
	{
		switch ($field->getType())
		{
			case FieldType::DATE:
				return new DateColumn($field->getName(),'Y-m-d');
			case FieldType::TIME:
				return new DateColumn($field->getName(),'g:i A');
			case FieldType::STRING:
			case FieldType::PARAGRAPH:
			case FieldType::INTEGER:
			case FieldType::FLOAT:
			case FieldType::BOOLEAN:
		}
		return new TextColumn($field->getName());
	}
}