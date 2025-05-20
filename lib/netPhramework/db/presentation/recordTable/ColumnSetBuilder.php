<?php

namespace netPhramework\db\presentation\recordTable;

use netPhramework\db\core\FieldSet;

class ColumnSetBuilder
{
	private ColumnMapper $mapper;
	private ?ColumnStrategy $strategy;
	private FieldSet $fieldSet;

	public function setMapper(ColumnMapper $mapper): self
	{
		$this->mapper = $mapper;
		return $this;
	}

	public function setStrategy(?ColumnStrategy $strategy): self
	{
		$this->strategy = $strategy;
		return $this;
	}

	public function setFieldSet(FieldSet $fieldSet): self
	{
		$this->fieldSet = $fieldSet;
		return $this;
	}

	public function build():ColumnSet
	{
		$columnSet = new ColumnSet();
		foreach($this->fieldSet as $field)
			$columnSet->add($this->mapper->mapColumn($field));
		$this->strategy?->configureColumnSet($columnSet);
		return $columnSet;
	}
}