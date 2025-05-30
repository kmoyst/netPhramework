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
	private Input $callbackInput;
	private ?ColumnMapper $columnMapper;
	private ?ColumnStrategy $columnStrategy;
	private RecordSet $recordSet;
	private MutablePath $compositePath;
	private FilterContext $filterContext;
	private ColumnSet $columnSet;
	private RowSet $rowSet;
	private AddButton $addButton;
	private View $selectFilterForm;
	private View $paginator;
	private ?Encodable $feedback;
	private RecordList $recordList;

	public function __construct()
	{
		$this->rowSet = new RowSet();
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

	public function setFilterContext(FilterContext $filterContext): self
	{
		$this->filterContext = $filterContext;
		return $this;
	}

	public function setColumnMapper(?ColumnMapper $columnMapper): self
	{
		$this->columnMapper = $columnMapper;
		return $this;
	}

	public function setColumnStrategy(?ColumnStrategy $columnStrategy): self
	{
		$this->columnStrategy = $columnStrategy;
		return $this;
	}

	public function setCallbackInput(Input $callbackInput): self
	{
		$this->callbackInput = $callbackInput;
		return $this;
	}

	public function setFeedback(?Encodable $feedback):self
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
		$this->columnSet = new ColumnSetBuilder()
			->setMapper($this->columnMapper ?? new ColumnMapper())
			->setStrategy($this->columnStrategy)
			->setFieldSet($this->recordSet->getFieldSet())
			->getColumnSet()
		;
		return $this;
	}

	/**
	 * @return $this
	 * @throws MappingException
	 */
	public function buildRowSet():self
	{
		$this->rowSet
			->setOrderedIds($this->recordSet->getIds())
			->setColumnSet($this->columnSet)
			->setCallbackInput($this->callbackInput)
			->setCompositePath($this->compositePath)
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
	public function applyFilter():self
	{
		$this->rowSet->setOrderedIds(new RecordFilterer()
			->setColumnSet($this->columnSet)
			->setRecordSet($this->recordSet)
			->setContext($this->filterContext)
			->getIds())
		;
		return $this;
	}

	public function buildAddButton():self
	{
		$this->addButton = new AddButton()
			->setCallbackInput($this->callbackInput)
			->setCompositePath(clone $this->compositePath)
		;
		return $this;
	}

	public function buildSelectFilterForm():self
	{
		$this->selectFilterForm = new SelectFilterDirector()
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
				->configure($this->filterContext)
				->buildPreviousForm()
				->buildNextForm()
				->getView()
			;
		}
		return $this;
	}

	public function buildRecordList():self
	{
		$this->recordList = new RecordList()
			->setColumnSet($this->columnSet)
			->setRowSet($this->rowSet)
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