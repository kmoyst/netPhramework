<?php

namespace netPhramework\db\mysql\queries;


use netPhramework\db\exceptions\MysqlException;
use netPhramework\db\mysql\Connection;
use netPhramework\db\mysql\Query;

readonly class ShowColumns implements Query
{
	public function __construct(
		private string $tableName, private Connection $adapter) {}

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