<?php

namespace netPhramework\db\mysql;
use Stringable;

readonly class FromCriteria implements Stringable
{
	public function __construct(private Criteria $criteria) {}

	public function get():string
	{
		if($this->criteria->isEmpty()) return '';
		$bits = [];
		foreach($this->criteria as $condition)
		{
			$f = $condition->getField()->getName();
			$o = $condition->getOperator()->value;
			$bits[] = "`$f` $o ?";
		}
		$glue = $this->criteria->isOr() ? ' OR ' : ' AND ';
		return "WHERE ".implode($glue, $bits);
	}

	public function __toString(): string
	{
		return $this->get();
	}
}