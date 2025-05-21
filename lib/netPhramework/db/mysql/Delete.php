<?php

namespace netPhramework\db\mysql;

use netPhramework\db\exceptions\MysqlException;
use netPhramework\db\mapping\Condition;
use netPhramework\db\mapping\DataSet;

class Delete implements \netPhramework\db\abstraction\Delete, Query
{
	private Criteria $criteria;

	public function __construct(
		private readonly string $tableName,
		private readonly Adapter $adapter)
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