<?php

namespace netPhramework\common;
use Countable;
use Stringable;

class Criteria implements Stringable, Countable
{
	/**
	 * @var Condition[]
	 */
	private array $conditions = [];

	public function add(Condition $condition):void
	{
		$this->conditions[] = $condition;
	}

	public function isEmpty():bool
	{
		return empty($this->conditions);
	}

	public function count(): int
	{
		return count($this->conditions);
	}

	public function getSql():string
	{
		$sql = [];
		foreach($this->conditions as $condition)
		{
			$k = $condition->getKey();
			$o = $condition->getOperator()->value;
			$sql[] = "`$k` $o ?";
		}
		return implode(' AND ', $sql);
	}

	public function getValues():array
	{
		$values = [];
		foreach ($this->conditions as $condition)
			$values[] = $condition->getValue();
		return $values;
	}

	public function __toString(): string
	{
		return $this->getSql();
	}
}