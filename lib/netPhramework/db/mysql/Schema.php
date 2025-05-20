<?php

namespace netPhramework\db\mysql;

use netPhramework\db\core\FieldSet;
use netPhramework\db\exceptions\MysqlException;

class Schema implements \netPhramework\db\mapping\Schema
{
	private string $idKey;
	private FieldSet $fieldSet;

	public function __construct(
		private readonly string $tableName,
		private readonly Adapter $adapter) {}

	public function getIdKey(): string
	{
		$this->cache();
		return $this->idKey;
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
		$this->idKey = $mapper->getPrimaryKey();
		$this->fieldSet = $mapper->getFieldSet();
	}
}