<?php

namespace netPhramework\data\mysql\crud;

use netPhramework\data\mapping\Condition;
use netPhramework\data\mapping\Criteria;
use netPhramework\data\mapping\DataSet;
use netPhramework\data\mysql\Connection;
use netPhramework\data\mysql\queries\adapters\FromCriteria;
use netPhramework\data\mysql\Query;

class Select implements \netPhramework\data\abstraction\crud\Select, Query
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