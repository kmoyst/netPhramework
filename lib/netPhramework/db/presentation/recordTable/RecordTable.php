<?php

namespace netPhramework\db\presentation\recordTable;
use netPhramework\common\Variables;
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
use netPhramework\rendering\Viewable;

class RecordTable extends Viewable
{
	private RecordSet $recordSet;
	private ?ColumnMapper $columnMapper;
	private ?ColumnStrategy $columnStrategy;
	private Input $callbackInput;
	private MutablePath $assetPath;
	private ?Encodable $feedback;

	private ColumnSet $columnSet;
	private RowSet $rowSet;
	private View $addbuttonView;
	private View $filterSelector;
	private View $paginator;
	private FilterContext $filterContext;

	public function setRecordSet(RecordSet $recordSet): self
	{
		$this->recordSet = $recordSet;
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

	public function setAssetPath(MutablePath $assetPath): self
	{
		$this->assetPath = $assetPath;
		return $this;
	}

	public function setFeedback(?Encodable $feedback): self
	{
		$this->feedback = $feedback;
		return $this;
	}

	public function applyFilter(FilterContext $context):self
	{
		$this->filterContext = $context;
		return $this;
	}

	/**
	 * @return $this
	 * @throws MappingException
	 */
	public function buildColumnSet():self
	{
		$this->columnSet = new ColumnSetBuilder()
			->setFieldSet($this->recordSet->getFieldSet())
			->setMapper($this->columnMapper ?? new ColumnMapper())
			->setStrategy($this->columnStrategy ?? null)
			->getColumnSet()
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
	public function buildRowSet():self
	{
		$orderedIds = isset($this->filterContext) ? new RecordFilterer()
			->setRecordSet($this->recordSet)
			->setColumnSet($this->columnSet)
			->setContext($this->filterContext)
			->getIds() : $this->recordSet->getIds()
		;
		$this->rowSet = new RowSet()
			->setRecordSet($this->recordSet)
			->setColumnSet($this->columnSet)
			->setAssetPath($this->assetPath)
			->setOrderedIds($orderedIds)
			->setCallbackInput($this->callbackInput)
		;
		return $this;
	}

	public function buildAddButtonView():self
	{
		$this->addbuttonView = new View('add-button-form')
			->add('action', (clone $this->assetPath)->append('add'))
			->add('callbackInput', $this->callbackInput)
		;
		return $this;
	}

	public function includeFilterSelector():self
	{
		if(isset($this->filterContext))
		{
			$this->filterSelector = new SelectFilterDirector()
				->configure($this->columnSet->getNames())
				->buildSelectFilterForm($this->filterContext)
				->getView()
			;
		}
		return $this;
	}

	public function includePaginator():self
	{
		if (isset($this->filterContext) && $this->filterContext->hasLimit()) {
			$this->paginator = new PaginatorDirector()
				->configure($this->filterContext)
				->buildPreviousForm()
				->buildNextForm()
				->getView();
		}
		return $this;
	}

	public function getTemplateName(): string
	{
		return 'record-table';
	}

	public function getVariables(): iterable
	{
		$list = $this->rowSet->count() === 0 ?'':new View('record-table-list')
			->add('headers',   $this->columnSet->getHeaders())
			->add('rows', 	   $this->rowSet)
			;
		return new Variables()
			->add('addButton', $this->addbuttonView)
			->add('feedback',  $this->feedback ?? '')
			->add('paginator', $this->paginator ?? '')
			->add('filterSelector', $this->filterSelector ?? '')
			->add('recordList', $list)
			;
	}
}