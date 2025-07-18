<?php

namespace netPhramework\db\mysql\crud;

use netPhramework\db\mapping\Condition;
use netPhramework\db\mapping\Criteria;
use netPhramework\db\mapping\DataSet;
use netPhramework\db\mysql\Connection;
use netPhramework\db\mysql\queries\adapters\FromCriteria;
use netPhramework\db\mysql\Query;

class Select implements \netPhramework\db\abstraction\crud\Select, Query
{
	private Criteria $criteria;

	public function __construct(private readonly string     $tableName,
								private readonly Connection $adapter)
	{
		$this->criteria = new Criteria();
	}
	public function where(Condition $condition):Select
	{
		$this->criteria->add($condition);
		return $this;
	}

	public function getData():array
	{
		return $this->adapter->runQuery($this)->fetchAll();
	}

	public function getMySql(): string
	{
		$fromCriteria = new FromCriteria($this->criteria);
		return rtrim("SELECT * FROM `$this->tableName` $fromCriteria");
	}

	public function getDataSet(): DataSet
	{
		return $this->criteria;
	}
}