<?php

namespace netPhramework\db\mysql;

use netPhramework\db\exceptions\MysqlException;
use netPhramework\db\mapping\Field;
use netPhramework\db\mapping\FieldSet;

class Schema implements \netPhramework\db\mapping\Schema
{
	private Field $primary;
	private FieldSet $fieldSet;

	public function __construct(
		private readonly string $tableName,
		private readonly Adapter $adapter) {}

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
		if(isset($this->idKey)) return;
		$mapper = new FieldMapper();
		$mapper->map(new FieldQuery($this->tableName,$this->adapter));
		$this->primary = $mapper->getPrimary();
		$this->fieldSet = $mapper->getFieldSet();
	}
}