<?php

namespace netPhramework\db\presentation\recordTable\views;

use netPhramework\core\Exception;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordNotFound;
use netPhramework\db\exceptions\ValueInaccessible;
use netPhramework\db\mapping\RecordSet;
use netPhramework\db\presentation\recordTable\{columnSet\ColumnMapper,
	columnSet\ColumnSet,
	query\Query,
	rowSet\RowRegistry,
	rowSet\RowSetFactory};
use netPhramework\locating\MutablePath;
use netPhramework\presentation\Input;
use netPhramework\rendering\Encodable;

class RecordTableBuilder
{
	protected RecordSet $recordSet;
	protected MutablePath $compositePath;
	protected Input $callbackInputForRows;
	protected ColumnSet $columnSet;

	protected ?Input $callbackInputForFilterForms = null;
	protected ?Encodable $feedback = null;

	protected RowSetFactory $factory;
	protected AddButton $addButton;
	protected RecordList $recordList;

	public function setRecordSet(RecordSet $recordSet): self
	{
		$this->recordSet = $recordSet;
		return $this;
	}

	public function setCompositePath(MutablePath $compositePath): self
	{
		$this->compositePath = $compositePath;
		return $this;
	}

	public function setCallbackInputForRows(Input $callbackInputForRows): self
	{
		$this->callbackInputForRows = $callbackInputForRows;
		return $this;
	}

	public function setCallbackInputForFilterForms(
		?Input $callbackInputForFilterForms): self
	{
		$this->callbackInputForFilterForms = $callbackInputForFilterForms;
		return $this;
	}

	public function setFeedback(?Encodable $feedback): self
	{
		$this->feedback = $feedback;
		return $this;
	}

	/**
	 * @return $this
	 * @throws MappingException
	 */
	public function buildColumnSet():self
	{
		$columnSet 	  = new ColumnSet();
		$columnMapper = new ColumnMapper();
		foreach($this->recordSet->getFieldSet() as $field)
			$columnSet->add($columnMapper->mapColumn($field));
		$this->columnSet = $columnSet;
		return $this;
	}

	/**
	 * @return $this
	 * @throws MappingException
	 */
	public function buildRowSetFactory():self
	{
		$registry = new RowRegistry()
			->setColumnSet($this->columnSet)
			->setCompositePath($this->compositePath)
			->setCallbackInput($this->callbackInputForRows)
			->setRecordSet($this->recordSet)
		;
		$this->factory = new RowSetFactory($registry)
			->initializeCollator($this->recordSet)
		;
		return $this;
	}

	/**
	 * @return $this
	 * @throws Exception
	 * @throws FieldAbsent
	 * @throws MappingException
	 * @throws RecordNotFound
	 * @throws ValueInaccessible
	 */
	public function applyQuery(Query $query):self
	{
		$this->factory->collate($query);
		return $this;
	}

	public function buildRecordList():self
	{
		$this->recordList = new RecordList()
			->setColumnSet($this->columnSet)
			->setRowSet($this->factory->getMappedRowSet())
		;
		return $this;
	}

	public function buildAddButton():self
	{
		$this->addButton = new AddButton()
			->setCallbackInput($this->callbackInputForRows)
			->setCompositePath(clone $this->compositePath)
		;
		return $this;
	}

	public function getRecordTable():RecordTable
	{
		return new RecordTable()
			->setAddButton($this->addButton)
			->setRecordList($this->recordList)
			->setSelectFilterForm($this->selectForm)
			->setPaginator($this->paginator)
			->setFeedback($this->feedback)
			;
	}
}