<?php

namespace netPhramework\data\mysql\crud;

use netPhramework\data\exceptions\MysqlException;
use netPhramework\data\mapping\Condition;
use netPhramework\data\mapping\Criteria;
use netPhramework\data\mapping\DataSet;
use netPhramework\data\mysql\Connection;
use netPhramework\data\mysql\queries\adapters\FromCriteria;
use netPhramework\data\mysql\Query;

class Delete implements \netPhramework\data\abstraction\crud\Delete, Query
{
	private Criteria $criteria;

	public function __construct(
		private readonly string     $tableName,
		private readonly Connection $adapter)
	{
		$this->criteria = new Criteria();
	}

	public function where(Condition $condition):Delete
	{
		$this->criteria->add($condition);
		return $this;
	}

	public function confirm():bool
	{
		if($this->criteria->isEmpty())
			throw new MysqlException("Delete queries must have conditions");
		return $this->adapter->runQuery($this)->getAffectedRows() >= 0;
	}

	public function getMySql(): string
	{
		$fromCriteria = new FromCriteria($this->criteria);
		return "DELETE FROM `$this->tableName` $fromCriteria";
	}

	public function getDataSet(): DataSet
	{
		return $this->criteria;
	}
}