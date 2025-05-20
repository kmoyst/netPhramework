<?php

namespace netPhramework\db\mysql;

use netPhramework\db\exceptions\MysqlException;

class Insert implements \netPhramework\db\mapping\Insert
{
	private array $data;

	public function __construct(
		private readonly string $tableName,
		private readonly Adapter $adapter) {}


	public function withData(array $data):Insert
    {
		$this->data = $data;
		return $this;
    }

    public function confirm():?string
    {
		try {
			$query = new Query(
				$this->assembleSql(), $this->assembleData());
			return $this->adapter->runQuery($query)->lastInsertId();
		} catch (MysqlException $e) {
			(new ExceptionFilter($e))->filterAndThrow();
		}
	}

	private function assembleSql():string
	{
		$columns = implode("`, `", array_keys($this->data));
		$values  = implode(', ', array_fill(0,count($this->data),'?'));
		return "INSERT INTO `$this->tableName` (`$columns`) VALUES ($values)";
	}

	private function assembleData():array
	{
		return array_values($this->data);
	}
}