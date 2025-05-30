<?php

namespace netPhramework\db\presentation\recordTable;

use netPhramework\db\mapping\FieldSet;

class ColumnSetBuilder
{
	private ColumnMapper $mapper;
	private ?ColumnStrategy $strategy =  null;
	private FieldSet $fieldSet;

	/**
	 * Dependency - Column Mapper
	 *
	 * @param ColumnMapper $mapper
	 * @return $this
	 */
	public function setMapper(ColumnMapper $mapper): self
	{
		$this->mapper = $mapper;
		return $this;
	}

	/**
	 * Dependency - Column Strategy
	 *
	 * @param ColumnStrategy|null $strategy
	 * @return $this
	 */
	public function setStrategy(?ColumnStrategy $strategy): self
	{
		$this->strategy = $strategy;
		return $this;
	}

	/**
	 * Dependency FieldSet
	 *
	 * @param FieldSet $fieldSet
	 * @return $this
	 */
	public function setFieldSet(FieldSet $fieldSet): self
	{
		$this->fieldSet = $fieldSet;
		return $this;
	}

	public function getColumnSet():ColumnSet
	{
		$columnSet = new ColumnSet();
		foreach($this->fieldSet as $field)
			$columnSet->add($this->mapper->mapColumn($field));
		$this->strategy?->configureColumnSet($columnSet);
		return $columnSet;
	}
}