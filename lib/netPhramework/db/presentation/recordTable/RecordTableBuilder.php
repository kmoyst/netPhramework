<?php

namespace netPhramework\db\presentation\recordTable;

use netPhramework\core\Exception;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordNotFound;
use netPhramework\db\exceptions\ValueInaccessible;
use netPhramework\db\mapping\RecordSet;
use netPhramework\locating\MutablePath;
use netPhramework\presentation\Input;
use netPhramework\rendering\Encodable;
use netPhramework\rendering\View;

class RecordTableBuilder
{
	protected RecordSet $recordSet;
	protected MutablePath $compositePath;
	protected Input $callbackInputForRows;
	protected ?Input $callbackInputForFilterForms;
	protected ?Encodable $feedback;
	protected FilterContext $filterContext;
	protected ColumnSet $columnSet;
	protected RowFactory $rowFactory;
	protected ?RowFilterer $filterer = null;
	protected AddButton $addButton;
	protected RecordList $recordList;
	protected View $selectFilterForm;
	protected View $paginator;

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

	public function setFilterContext(FilterContext $filterContext): self
	{
		$this->filterContext = $filterContext;
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

	public function buildRowFactory():self
	{
		$this->rowFactory = new RowFactory()
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
	public function applyRowFilter():self
	{
		$this->filterer = new RowFilterer()
			->setContext($this->filterContext)
			->setFactory($this->rowFactory)
			->setAllIds($this->recordSet->getIds())
			->select()
			->sort()
			->paginate()
		;
		return $this;
	}

	public function sort():self
	{
		$rowSet = new RowSet()
			->setFactory($this->rowFactory)
			->setTraversible($this->recordSet->getIds());
		new RowSorter()
			->setRowSet($rowSet)
			->setSortArray($this->filterContext->getSortArray())
			->sort();
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
		$this->selectFilterForm = new SelectFilterDirector()
			->setCallbackInput($this->callbackInputForFilterForms ?? null)
			->setColumnNames($this->columnSet->getNames())
			->buildSelectFilterForm($this->filterContext)
			->getView()
		;
		return $this;
	}

	public function buildPaginator():self
	{
		if($this->filterContext->getLimit() !== null)
		{
			$this->paginator = new PaginatorDirector()
				->configure($this->filterContext,
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
				$this->filterer?->getProcessedRowSet() ?? new RowSet()
				->setTraversible($this->recordSet->getIds())
				->setFactory($this->rowFactory)
			)
		;
		return $this;
	}

	public function getRecordTable():RecordTable
	{
		return new RecordTable()
			->setAddButton($this->addButton)
			->setRecordList($this->recordList)
			->setSelectFilterForm($this->selectFilterForm ?? null)
			->setPaginator($this->paginator ?? null)
			->setFeedback($this->feedback ?? null)
			;
	}
}