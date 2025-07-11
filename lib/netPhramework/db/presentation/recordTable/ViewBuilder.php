<?php

namespace netPhramework\db\presentation\recordTable;

use netPhramework\db\core\RecordSet;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordNotFound;
use netPhramework\db\exceptions\ValueInaccessible;
use netPhramework\db\presentation\recordTable\{collation\CollationMap,
	collation\Collator,
	collation\Query,
	columnSet\ColumnMapper,
	columnSet\ColumnSet,
	columnSet\ColumnSetStrategy,
	rowSet\RowSet,
	rowSet\RowSetFactory};
use netPhramework\exceptions\Exception;
use netPhramework\routing\MutablePath;
use netPhramework\rendering\Encodable;
use netPhramework\rendering\View;

class ViewBuilder
{
	private RecordSet $recordSet;
	private MutablePath $compositePath;
	private Encodable $callbackInputForRows;
	private Query $query;

	private ?Encodable $callbackInputForFilterForms = null;
	private ?Encodable $feedback = null;

	protected ColumnSet $columnSet;
	protected CollationMap $collationMap;
	protected RowSetFactory $rowSetFactory;

	/**
	 * @return $this
	 * @throws MappingException
	 */
	public function buildColumnSet(?ColumnSetStrategy $strategy):self
	{
		$columnMapper    = new ColumnMapper();
		$this->columnSet = new ColumnSet();
		foreach($this->recordSet->getFieldSet() as $field)
			$this->columnSet->add($columnMapper->mapColumn($field));
		$strategy?->configureColumnSet($this->columnSet);
		return $this;
	}

	public function buildRowSetFactory():self
	{
		$this->rowSetFactory = new RowSetFactory()
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
	public function collate():self
	{
		$this->collationMap = new Collator()
			->setQuery($this->query)
			->setRecordSet($this->recordSet)
			->setColumnSet($this->columnSet)
			->initialize()
			->select()
			->sort()
			->paginate()
			->getMap()
			;
		return $this;
	}

	/**
	 * @param ViewStrategy|null $strategy
	 * @param bool $includeQueryForm
	 * @return View
	 */
	public function generateView(?ViewStrategy $strategy,
								 bool $includeQueryForm = true):View
	{
		$viewFactory 		= new ViewFactory($this->query);
		$columnSet 			= $this->columnSet;
		$columnNames		= $columnSet->getNames();
		$rowSet				= $this->generateRecordListRowSet();
		$rowSetFactory		= $this->rowSetFactory;
		$rowCallback 		= $this->callbackInputForRows;
		$formCallback   	= $this->callbackInputForFilterForms;
		$compositePath 		= clone $this->compositePath;
		$collationMap		= $this->collationMap;
		if($includeQueryForm)
		{
			$paginator  = $viewFactory->getPaginator($formCallback);
			$selectForm = $viewFactory
				->getSelectForm($columnNames, $formCallback);
		}
		$recordList = $viewFactory->getRecordList($columnSet, $rowSet);
		$addButton  = $viewFactory->getAddButton($compositePath, $rowCallback)
		;
		$view = new View('record-table')
			->add('addButton', $addButton)
			->add('recordList', $recordList)
			->add('filterSelector', $selectForm ?? '')
			->add('paginator', $paginator ?? '')
			->add('feedback', $this->feedback ?? '')
			;
		$context = new ViewContext($view, $rowSetFactory, $collationMap);
		$strategy?->configureView($context);
		return $view;
	}

	private function generateRecordListRowSet():RowSet
	{
		$rowSetFactory = $this->rowSetFactory;
		$rowIds		   = $this->collationMap->getPaginatedIds();
		return $rowSetFactory->makeRowSet($rowIds);
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

	public function setCallbackInputForRows(
		Encodable $callbackInputForRows): self
	{
		$this->callbackInputForRows = $callbackInputForRows;
		return $this;
	}

	public function setCallbackInputForFilterForms(
		?Encodable $callbackInputForFilterForms): self
	{
		$this->callbackInputForFilterForms = $callbackInputForFilterForms;
		return $this;
	}

	public function setFeedback(?Encodable $feedback): self
	{
		$this->feedback = $feedback;
		return $this;
	}

	public function setQuery(Query $query): self
	{
		$this->query = $query;
		return $this;
	}
}