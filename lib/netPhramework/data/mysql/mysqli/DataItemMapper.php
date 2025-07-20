<?php

namespace netPhramework\data\mysql\mysqli;

use netPhramework\data\exceptions\MysqlException;
use netPhramework\data\mapping\DataItem;
use netPhramework\data\mapping\FieldType;

class DataItemMapper
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
		if($value === '' || $value === null)
		{
			$this->bindings->addQueryValue(null);
			$valueSet = true;
		} else $valueSet = false;
		switch($item->getField()->getType())
		{
			case FieldType::STRING:
			case FieldType::PARAGRAPH:
			case FieldType::DATE:
			case FieldType::TIME:
				$this->bindings->addType('s');
				if(!$valueSet)
					$this->bindings->addQueryValue($value);
				break;
			case FieldType::BOOLEAN:
			case FieldType::INTEGER:
				$this->bindings->addType('i');
				if($valueSet) break;
				if(!is_numeric($value))
					throw new MysqlException("Invalid value. Non numeric");
				$this->bindings->addQueryValue((int)$value);
				break;
			case FieldType::FLOAT:
				$this->bindings->addType('d');
				if($valueSet) break;
				if(!is_numeric($value))
					throw new MysqlException("Invalid value. Non numeric");
				$this->bindings->addQueryValue((float)$value);
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