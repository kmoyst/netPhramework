<?php

namespace netPhramework\data\presentation\recordTable\collation;

use netPhramework\common\Variables;
use netPhramework\data\mapping\Glue;
use netPhramework\data\mapping\Operator;
use netPhramework\data\mapping\SortDirection;

class QueryBuilder extends Variables
{
	public function addCondition(
		string $fieldName,
		string $value,
		?Operator $operator = null,
		?Glue $glue = null):self
	{
		$setKey 	 				= QueryKey::CONDITION_SET->value;
		$fieldKey 	 				= QueryKey::CONDITION_FIELD->value;
		$valueKey 	 				= QueryKey::CONDITION_VALUE->value;
		$operatorKey 				= QueryKey::CONDITION_OPERATOR->value;
		$glueKey	 				= QueryKey::CONDITION_GLUE->value;
		$condition   				= [];
		$condition[$fieldKey] 	 	= $fieldName;
		$condition[$valueKey] 	 	= $value;
		$condition[$operatorKey] 	= ($operator ?? Operator::EQUAL)->value;
		$condition[$glueKey]		= ($glue ?? Glue::AND)->value;
		$criteria 					= $this->getOrNull($setKey) ?? [];
		$criteria[] 				= $condition;
		$this->add($setKey, $criteria);
		return $this;
	}

	public function addSortVector(
		string $fieldName,
		?SortDirection $direction = null): self
	{
		$setKey 				= QueryKey::SORT_ARRAY->value;
		$fieldKey 				= QueryKey::SORT_FIELD->value;
		$directionKey 			= QueryKey::SORT_DIRECTION->value;
		$vector 				= [];
		$vector[$fieldKey] 		= $fieldName;
		$vector[$directionKey] 	= ($direction??SortDirection::ASCENDING)->value;
		$sortArray 				= $this->getOrNull($setKey) ?? [];
		$sortArray[] 			= $vector;
		$this->add($setKey, $sortArray);
		return $this;
	}

	public function setLimit(int $limit):self
	{
		$this->add(QueryKey::LIMIT->value, $limit);
		return $this;
	}

	public function setOffset(int $offset):self
	{
		$this->add(QueryKey::OFFSET->value, $offset);
		return $this;
	}
}