<?php

namespace netPhramework\db\mysql;

use netPhramework\common\Condition;
use netPhramework\common\Criteria;
use netPhramework\common\Operator;
use netPhramework\db\exceptions\MysqlException;

class Delete implements \netPhramework\db\mapping\Delete
{
	private Criteria $criteria;

	public function __construct(
		private readonly string $tableName,
		private readonly Adapter $adapter)
	{
		$this->criteria = new Criteria();
	}

	public function where(string $key, string $value,
						  Operator $operator = Operator::EQUAL):Delete
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
			throw new MysqlException("Delete queries must have conditions");
		$sql = "DELETE FROM `$this->tableName` WHERE $this->criteria";
		$data = $this->criteria->getValues();
 		$query = new Query($sql, $data);
		return $this->adapter->runQuery($query)->getAffectedRows() >= 0;
	}
}