<?php

namespace netPhramework\data\mysql\queries\adapters;
use netPhramework\data\exceptions\MysqlException;
use netPhramework\data\mapping\Criteria;
use netPhramework\data\mysql\MysqlOperator;
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
			$o = MysqlOperator::fromMappingOperator($condition->getOperator());
			$bits[] = "`$f` $o->value ?";
		}
		$glue = ' AND ';
		return "WHERE ".implode($glue, $bits);
	}

    /**
     * @return string
     * @throws MysqlException
     */
	public function __toString(): string
	{
		return $this->get();
	}
}