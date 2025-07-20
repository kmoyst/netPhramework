<?php

namespace netPhramework\data\mysql\crud;

use netPhramework\data\exceptions\MappingException;
use netPhramework\data\exceptions\MysqlException;
use netPhramework\data\mapping\Condition;
use netPhramework\data\mapping\Criteria;
use netPhramework\data\mapping\DataItem;
use netPhramework\data\mapping\DataSet;
use netPhramework\data\mysql\Connection;
use netPhramework\data\mysql\ExceptionRefiner;
use netPhramework\data\mysql\queries\adapters\FromCriteria;
use netPhramework\data\mysql\Query;

class Update implements \netPhramework\data\abstraction\crud\Update, Query, DataSet
{
	private DataSet $dataSet;
	private Criteria $criteria;
	private bool $iteratedData = false;

	public function __construct(
		private readonly string     $tableName,
		private readonly Connection $adapter)
	{
		$this->criteria = new Criteria();
	}

	public function withData(DataSet $dataSet):Update
	{
		$this->dataSet = $dataSet;
		return $this;
	}

	public function where(Condition $condition):Update
	{
		$this->criteria->add($condition);
		return $this;
	}

	public function confirm():bool
	{
		if(count($this->criteria) === 0)
			throw new MappingException("Update queries must have conditions");
		elseif(!isset($this->dataSet))
			throw new MappingException("Data not set for update");
		try {
			return $this->adapter
					->runQuery($this)
					->getAffectedRows() >= 0
				;
		} catch (MysqlException $e) {
			new ExceptionRefiner($e)->refineAndThrow();
		}
	}

	public function getMySql(): string
	{
		$bits = [];
		foreach($this->dataSet->getFieldNames() as $k)
			$bits[] = "`$k` = ?";
		$props = implode(', ', $bits);
		$fromCriteria = new FromCriteria($this->criteria);
		return "UPDATE `$this->tableName` SET $props $fromCriteria";
	}

	public function getDataSet(): DataSet
	{
		return $this;
	}

	public function rewind(): void
	{
		$this->iteratedData = false;
		$this->dataSet->rewind();
		$this->criteria->rewind();
	}

	public function valid(): bool
	{
		if($this->iteratedData)
			return $this->criteria->valid();
		elseif($this->dataSet->valid())
			return true;
		else
		{
			$this->iteratedData = true;
			return $this->criteria->valid();
		}
	}

	public function current(): DataItem
	{
		if($this->iteratedData) return $this->criteria->current();
		else return $this->dataSet->current();
	}
	public function key(): int
	{
		if($this->iteratedData)
			return $this->criteria->key();
		else
			return $this->dataSet->key();
	}

	public function next(): void
	{
		if($this->iteratedData)
			$this->criteria->next();
		else
			$this->dataSet->next();
	}

	public function count(): int
	{
		return count($this->dataSet) + count($this->criteria);
	}

	public function getFieldNames(): array
	{
		return array_merge(
			$this->dataSet->getFieldNames(), $this->criteria->getFieldNames());
	}
}