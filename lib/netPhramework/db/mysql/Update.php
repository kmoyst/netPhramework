<?php

namespace netPhramework\db\mysql;

use netPhramework\common\Condition;
use netPhramework\common\Criteria;
use netPhramework\common\Operator;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\MysqlException;

class Update implements \netPhramework\db\mapping\Update
{
	private array $rowData;
	private Criteria $criteria;

	public function __construct(
		private readonly string $tableName,
		private readonly Adapter $adapter)
	{
		$this->criteria = new Criteria();
	}

	public function withData(array $rowData):Update
	{
		$this->rowData = $rowData;
		return $this;
	}

	public function where(string $key, string $value,
						  Operator $operator = Operator::EQUAL):Update
	{
		$c = new Condition();
		$c->setKey($key);
		$c->setValue($value);
		$c->setOperator($operator);
		$this->criteria->add($c);
		return $this;
	}

	public function confirm():bool
	{
		if($this->criteria->isEmpty())
			throw new MappingException("Update queries must have conditions");
		try {
			$stmt = new Query(
				$this->assembleSql(), $this->assembleData());
			return $this->adapter
					->runQuery($stmt)
					->getAffectedRows() >= 0;
		} catch (MysqlException $e) {
			(new ExceptionFilter($e))->filterAndThrow();
		}
	}

	private function assembleSql(): string
	{
		$bits = [];
		foreach(array_keys($this->rowData) as $k)
			$bits[] = "`$k` = ?";
		$props = implode(', ', $bits);
		return "UPDATE `$this->tableName` SET $props WHERE $this->criteria";
	}

	private function assembleData(): ?array
	{
		$a1 = array_values($this->rowData);
		$a2 = $this->criteria->getValues();
		return array_merge($a1, $a2);
	}
}