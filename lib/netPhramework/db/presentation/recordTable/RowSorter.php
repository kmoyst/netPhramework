<?php

namespace netPhramework\db\presentation\recordTable;

use netPhramework\db\mapping\SortDirection;

class RowSorter
{
	private array $sortArray;
	private RowSet $rowSet;

	public function setSortArray(array $sortArray): self
	{
		$this->sortArray = $sortArray;
		return $this;
	}

	public function setRowSet(RowSet $rowSet): self
	{
		$this->rowSet = $rowSet;
		return $this;
	}

	public function sort():void
	{
		$args = [];
		foreach($this->sortArray as $vector)
		{
			$field = $vector[FilterKey::SORT_FIELD->value];
			$direction = $vector[FilterKey::SORT_DIRECTION->value];
			if(empty($field)) break;
			$parsedDirection = SortDirection::tryFrom($direction);
			$values = [];
			foreach($this->rowSet as $row)
				$values[] = $row->getSortableValue($field);
			$args[] = $values;
			$args[] = $parsedDirection ===
			SortDirection::DESCENDING ? SORT_DESC : SORT_ASC;
			$args[] = SORT_STRING | SORT_NATURAL | SORT_FLAG_CASE;
		}
		$args[] = $this->rowSet->getIds();
		array_multisort(...$args);
		$this->rowSet->setTraversible(array_pop($args));
	}
}