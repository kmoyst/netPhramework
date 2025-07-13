<?php

namespace netPhramework\db\presentation\recordTable\collation;

use netPhramework\db\mapping\Glue;
use netPhramework\db\mapping\Operator;
use netPhramework\exceptions\Exception;

class CollatorCondition
{
	private Operator $operator;
	private ?Glue $glue;
	private string $field;
	private string $value;

	public function __construct(private readonly array $queryCondition) {}

	/**
	 * @return bool
	 * @throws Exception
	 */
	public function parse():bool
	{
		$condition 	 = $this->queryCondition;
		$strOperator = $condition[QueryKey::CONDITION_OPERATOR->value];
		$strGlue 	 = $condition[QueryKey::CONDITION_GLUE->value] ?? '';
		$field 		 = $condition[QueryKey::CONDITION_FIELD->value];
		$value 		 = $condition[QueryKey::CONDITION_VALUE->value];
		if($field == '' || $value == '')
		{
			return false;
		}
		else
		{
			$this->field = $field;
			$this->value = $value;
		}
		if(($this->operator = Operator::tryFrom($strOperator)) === null)
		{
			throw new Exception("Invalid Operator: $strOperator");
		}
		if($strGlue === '')
		{
			$this->glue = null;
			return true;
		}
		elseif(($this->glue = Glue::tryFrom($strGlue)) === null)
		{
			throw new Exception("Invalid Glue: $strGlue");
		}
		else
		{
			return true;
		}
	}

	public function getOperator(): Operator
	{
		return $this->operator;
	}

	public function getGlue(): ?Glue
	{
		return $this->glue;
	}

	public function getField(): string
	{
		return $this->field;
	}

	public function getValue(): string
	{
		return $this->value;
	}
}