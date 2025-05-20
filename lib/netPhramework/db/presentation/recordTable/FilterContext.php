<?php

namespace netPhramework\db\presentation\recordTable;

use netPhramework\common\Variables;
use netPhramework\db\core\RecordSet;

class FilterContext implements FilterFormContext
{
	private RecordSet $recordSet;
	private Variables $variables;
	private ?string $sortField = null;
	private int $sortDirection = 0;
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
		$limit = $vars->getOrNull(FilterKeys::LIMIT->value);
		$offset = $vars->getOrNull(FilterKeys::OFFSET->value) ?? 0;
		$sortField = $vars->getOrNull(FilterKeys::SORT_FIELD->value);
		$sortDirection = $vars->getOrNull(FilterKeys::SORT_DIRECTION->value);
		$this->count = count($recordSet);
		$this->limit = is_numeric($limit) && $limit > 0 ? $limit : null;
		$this->offset = $offset < $this->count ? $offset : 0;
		$this->sortField = $sortField !== '' ? $sortField : null;
		$this->sortDirection = (int)$sortDirection === 1 ? 1 : 0;
		return $this;
	}

	public function getSortField(): ?string
	{
		return $this->sortField;
	}

	public function getSortDirection(): int
	{
		return $this->sortDirection;
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