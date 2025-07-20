<?php

namespace netPhramework\data\mysql\crud;

use netPhramework\data\exceptions\MappingException;
use netPhramework\data\exceptions\MysqlException;
use netPhramework\data\mapping\DataSet;
use netPhramework\data\mysql\Connection;
use netPhramework\data\mysql\ExceptionRefiner;
use netPhramework\data\mysql\Query;

class Insert implements \netPhramework\data\abstraction\crud\Insert, Query
{
	private DataSet $dataSet;

	public function __construct(
		private readonly string     $tableName,
		private readonly Connection $adapter) {}


	public function withData(DataSet $dataSet):Insert
    {
		$this->dataSet = $dataSet;
		return $this;
    }

    public function confirm():?string
    {
		if(!isset($this->dataSet))
			throw new MappingException("Data not set for update");
		try {
			return $this->adapter->runQuery($this)->lastInsertId();
		} catch (MysqlException $e) {
			new ExceptionRefiner($e)->refineAndThrow();
		}
	}

	public function getMySql():string
	{
		$columns = implode("`, `", $this->dataSet->getFieldNames());
		$values  = implode(', ', array_fill(0,count($this->dataSet),'?'));
		return "INSERT INTO `$this->tableName` (`$columns`) VALUES ($values)";
	}

	public function getDataSet():DataSet
	{
		return $this->dataSet;
	}
}