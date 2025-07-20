<?php

namespace netPhramework\data\mysql;

use netPhramework\data\exceptions\MysqlException;
use netPhramework\data\mapping\Field;
use netPhramework\data\mapping\FieldSet;
use netPhramework\data\mysql\queries\ShowColumns;

class Schema implements \netPhramework\data\abstraction\Schema
{
	private Field $primary;
	private FieldSet $fieldSet;

	public function __construct(
		private readonly string     $tableName,
		private readonly Connection $adapter) {}

	public function getPrimary(): Field
	{
		$this->cache();
		return $this->primary;
	}

	public function getFieldSet(): FieldSet
	{
		$this->cache();
		return $this->fieldSet;
	}

	/**
	 * @return void
	 * @throws MysqlException
	 */
	private function cache():void
	{
		if(isset($this->primary)) return;
		$mapper = new FieldMapper();
		$mapper->map(new ShowColumns($this->tableName,$this->adapter));
		$this->primary = $mapper->getPrimary();
		$this->fieldSet = $mapper->getFieldSet();
	}
}