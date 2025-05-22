<?php

namespace netPhramework\db\mysql\mysqli;

use netPhramework\db\exceptions\MysqlException;
use netPhramework\db\mapping\DataItem;
use netPhramework\db\mapping\FieldType;

class DataMapper
{
	private Bindings $bindings;

	public function __construct()
	{
		$this->bindings = new Bindings();
	}

	/**
	 * @param DataItem $item
	 * @return void
	 * @throws MysqlException
	 */
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
			default:
				throw new MysqlException("Field type note mapped");
		}

	}

	public function getBindings():Bindings
	{
		return $this->bindings;
	}
}