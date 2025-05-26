<?php

namespace netPhramework\db\presentation\recordTable;

use netPhramework\common\Variables;
use netPhramework\db\core\RecordSet;

class FilterContext implements FilterFormContext
{
	private RecordSet $recordSet;
	private Variables $variables;
	private array $conditionSet;
	private array $sortArray;
	private ?int $limit;
	private int $offset = 0;
	private int $count;

	public function setRecordSet(RecordSet $recordSet): self
	{
		$this->recordSet = $recordSet;
		return $this;
	}

	public function setVariables(Variables $variables): self
	{
		$this->variables = $variables;
		return $this;
	}

	public function parse():self
	{
		$vars = $this->variables;
		$recordSet = $this->recordSet;
		$limit = $vars->getOrNull(FilterKey::LIMIT->value);
		$offset = $vars->getOrNull(FilterKey::OFFSET->value) ?? 0;
		$sortArray = $vars->getOrNull(FilterKey::SORT_ARRAY->value) ?? [];
		$conditions = $vars->getOrNull(FilterKey::CONDITION_SET->value) ?? [];
		$this->count = count($recordSet);
		$this->limit = is_numeric($limit) && $limit > 0 ? $limit : null;
		$this->offset = $offset < $this->count ? $offset : 0;
		$this->sortArray = $sortArray;
		$this->conditionSet = $conditions;
		return $this;
	}

	public function getConditionSet(): array
	{
		if(empty($this->conditionSet)) return [];
		$conditionSet = $this->conditionSet;
		if(empty($conditionSet[count($conditionSet)-1]
		[FilterKey::CONDITION_FIELD->value])) array_pop($conditionSet);
		return $conditionSet;
	}

	public function getSortArray(): array
	{
		if(empty($this->sortArray)) return [];
		$sortArray = $this->sortArray;
		if(empty($sortArray[count($sortArray)-1]
		[FilterKey::SORT_FIELD->value])) array_pop($sortArray);
		return $sortArray;
	}

	public function getLimit(): ?int
	{
		return $this->limit;
	}

	public function getOffset(): int
	{
		return $this->offset;
	}

	public function getCount(): int
	{
		return $this->count;
	}
}