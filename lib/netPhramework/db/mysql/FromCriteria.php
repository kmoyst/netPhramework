<?php

namespace netPhramework\db\mysql;
use netPhramework\db\exceptions\MysqlException;
use netPhramework\db\mapping\Criteria;
use Stringable;

readonly class FromCriteria implements Stringable
{
	public function __construct(private Criteria $criteria) {}

	/**
	 * @return string
	 * @throws MysqlException
	 */
	public function get():string
	{
		if($this->criteria->isEmpty()) return '';
		$bits = [];
		foreach($this->criteria as $condition)
		{
			$f = $condition->getField()->getName();
			$o = Operator::fromMappingOperator($condition->getOperator());
			$bits[] = "`$f` $o->value ?";
		}
		$glue = $this->criteria->isOr() ? ' OR ' : ' AND ';
		return "WHERE ".implode($glue, $bits);
	}

	public function __toString(): string
	{
		return $this->get();
	}
}