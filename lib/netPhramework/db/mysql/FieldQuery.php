<?php

namespace netPhramework\db\mysql;


use netPhramework\db\exceptions\MysqlException;

readonly class FieldQuery
{
	public function __construct(
		private string $tableName, private Adapter $adapter) {}

	/**
	 * @return array
	 * @throws MysqlException
	 */
	public function provideSqlColumns(): array
	{
		$query = new Query("SHOW COLUMNS FROM `$this->tableName`");
		return $this->adapter->runQuery($query)->fetchAll();
	}
}