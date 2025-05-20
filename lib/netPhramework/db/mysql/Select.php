<?php

namespace netPhramework\db\mysql;

use netPhramework\common\Condition;
use netPhramework\common\Criteria;
use netPhramework\common\Operator;

class Select implements \netPhramework\db\mapping\Select
{
	private string $name;
	private array $fieldNames;
	private Criteria $criteria;

	public function __construct(string $name,
								private readonly Adapter $adapter)
	{
		$this->name = $name;
		$this->criteria = new Criteria();
	}

	public function setFieldNames(array $names):Select
	{
		$this->fieldNames = $names;
		return $this;
	}

	public function where(string $key,
						  string $value,
						  Operator $operator = Operator::EQUAL):Select
	{
		$c = new Condition();
		$c->setKey($key);
		$c->setValue($value);
		$c->setOperator($operator);
		$this->criteria->add($c);
		return $this;
	}

	public function getData():array
	{
		$query = new Query(
			$this->assembleSql(), $this->assembleData());
		return $this->adapter->runQuery($query)->fetchAll();
	}

	private function assembleSql(): string
	{
		if(isset($this->fieldNames))
			$fieldPart = '`'.implode(`, `, $this->fieldNames).'`';
		else
			$fieldPart = '*';
		$tail = (!$this->criteria->isEmpty()) ? " WHERE $this->criteria" : '';
		return "SELECT $fieldPart FROM `$this->name`$tail";
	}

	private function assembleData(): ?array
	{
		return $this->criteria->getValues() ?? null;
	}
}