<?php

namespace netPhramework\db\mysql\crud;

use netPhramework\db\exceptions\MysqlException;
use netPhramework\db\mapping\Condition;
use netPhramework\db\mapping\Criteria;
use netPhramework\db\mapping\DataSet;
use netPhramework\db\mysql\Connection;
use netPhramework\db\mysql\queries\adapters\FromCriteria;
use netPhramework\db\mysql\Query;

class Delete implements \netPhramework\db\abstraction\crud\Delete, Query
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