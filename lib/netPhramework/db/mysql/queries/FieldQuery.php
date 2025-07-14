<?php

namespace netPhramework\db\mysql\queries;


use netPhramework\db\exceptions\MysqlException;
use netPhramework\db\mysql\Adapter;
use netPhramework\db\mysql\Query;

readonly class FieldQuery implements Query
{
	public function __construct(
		private string $tableName, private Adapter $adapter) {}

	/**
	 * @return array
	 * @throws MysqlException
	 */
	public function provideSqlColumns(): array
	{
		return $this->adapter->runQuery($this)->fetchAll();
	}

	public function getMySql(): string
	{
		return "SHOW COLUMNS FROM `$this->tableName`";
	}

	public function getDataSet(): null
	{
		return null;
	}
}