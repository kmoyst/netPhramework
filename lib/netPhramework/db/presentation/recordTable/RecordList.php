<?php

namespace netPhramework\db\presentation\recordTable;
use netPhramework\common\Variables;
use netPhramework\rendering\Viewable;

class RecordList extends Viewable
{
	private ColumnSet $columnSet;
	private RowSet $rowSet;

	public function setColumnSet(ColumnSet $columnSet): self
	{
		$this->columnSet = $columnSet;
		return $this;
	}

	public function setRowSet(RowSet $rowSet): self
	{
		$this->rowSet = $rowSet;
		return $this;
	}

	public function getTemplateName(): string
	{
		return 'record-table-list';
	}

	public function getVariables(): Variables
	{
		return new Variables()
			->add('headers',   $this->columnSet->getHeaders())
			->add('rows', 	   $this->rowSet)
			;
	}
}