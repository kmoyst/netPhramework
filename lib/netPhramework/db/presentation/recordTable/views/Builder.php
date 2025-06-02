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
	paginator\Director as paginatorDirector,
	query\Query,
	rowSet\RowMapper,
	rowSet\RowRegistry,
	rowSet\RowSet,
	selectForm\Director as selectFormDirector};
use netPhramework\locating\MutablePath;
use netPhramework\presentation\Input;
use netPhramework\rendering\Encodable;
use netPhramework\rendering\View;

class Builder
{
	protected Query $query;
	protected RecordSet $recordSet;
	protected MutablePath $compositePath;
	protected Input $callbackInputForRows;
	protected ColumnSet $columnSet;
	protected RowRegistry $registry;
	protected AddButton $addButton;
	protected RecordList $recordList;

	protected ?Input $callbackInputForFilterForms = null;
	protected ?Encodable $feedback = null;
	protected ?RowMapper $rowMapper = null;
	protected ?View $selectForm = null;
	protected ?View $paginator = null;

	public function setQuery(Query $query): self
	{
		$this->query = $query;
		return $this;
	}

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

	public function buildRowRegistry():self
	{
		$this->registry = new RowRegistry()
			->setColumnSet($this->columnSet)
			->setCompositePath($this->compositePath)
			->setCallbackInput($this->callbackInputForRows)
			->setRecordSet($this->recordSet)
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
	public function mapRows():self
	{
		$this->rowMapper = new RowMapper()
			->setQuery($this->query)
			->setRegistry($this->registry)
			->setAllIds($this->recordSet->getIds())
			->select()
			->sort()
			->paginate()
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

	public function buildSelectFilterForm():self
	{
		$this->selectForm = new selectFormDirector()
			->setCallbackInput($this->callbackInputForFilterForms ?? null)
			->setColumnNames($this->columnSet->getNames())
			->buildSelectFilterForm($this->query)
			->getView()
		;
		return $this;
	}

	public function buildPaginator():self
	{
		if($this->query->getLimit() !== null)
		{
			$this->paginator = new paginatorDirector()
				->configure($this->query,
					$this->callbackInputForFilterForms ?? null)
				->buildPreviousForm()
				->buildNextForm()
				->getView()
			;
		}
		return $this;
	}

	/**
	 * @return $this
	 * @throws MappingException
	 */
	public function buildRecordList():self
	{
		$this->recordList = new RecordList()
			->setColumnSet($this->columnSet)
			->setRowSet(
				$this->rowMapper?->getProcessedRowSet() ?? new RowSet()
				->setCollation($this->recordSet->getIds())
				->setRegistry($this->registry)
			)
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