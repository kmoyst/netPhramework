<?php

namespace netPhramework\db\mysql\mysqli;

use netPhramework\db\exceptions\MappingException;
use netPhramework\db\mapping\DataItem;
use netPhramework\db\mapping\DataSet;
use netPhramework\db\mapping\FieldType;

class DataMapper
{
	private Bindings $bindings;

	public function __construct()
	{
		$this->bindings = new Bindings();
	}

	public function mapItem(DataItem $item):void
	{
		$value = $item->getValue();
		$this->bindings->addQueryValue(
			$value === '' || $value === null ? null : $value);
		switch($item->getField()->getType())
		{
			case FieldType::STRING:
			case FieldType::PARAGRAPH:
			case FieldType::DATE:
			case FieldType::TIME:
				$this->bindings->addType('s');
				break;
			case FieldType::BOOLEAN:
			case FieldType::INTEGER:
				$this->bindings->addType('i');
				break;
			case FieldType::FLOAT:
				$this->bindings->addType('d');
				break;
		}
	}

	public function getBindings():Bindings
	{
		return $this->bindings;
	}
}