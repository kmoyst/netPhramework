<?php

namespace netPhramework\db\presentation\recordTable;

use netPhramework\db\presentation\recordTable\
{
	columnSet\ColumnMapper,
	columnSet\ColumnSet,
	query\Query,
	rowSet\CollationMap,
	rowSet\Collator,
	rowSet\RowSet,
	rowSet\RowSetFactory
};
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
	private RecordSet $recordSet;
	private MutablePath $compositePath;
	private Input $callbackInputForRows;
	private Query $query;

	private ?RecordTableStrategy $strategy;
	private ?Input $callbackInputForFilterForms = null;
	private ?Encodable $feedback = null;

	protected ColumnSet $columnSet;
	protected CollationMap $collationMap;
	protected RowSetFactory $rowSetFactory;

	/**
	 * @return $this
	 * @throws MappingException
	 */
	public function buildColumnSet():self
	{
		$this->columnSet = new ColumnSet();
		$columnMapper = new ColumnMapper();
		foreach($this->recordSet->getFieldSet() as $field)
			$this->columnSet->add($columnMapper->mapColumn($field));
		$this->strategy?->configureColumnSet($this->columnSet);
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

	public function generateView(bool $includeQueryInput = true):View
	{
		$columnSet 			= $this->columnSet;
		$columnNames		= $columnSet->getNames();
		$rowSet				= $this->generateRecordListRowSet();
		$rowCallback 		= $this->callbackInputForRows;
		$formCallback   	= $this->callbackInputForFilterForms;
		$compositePath 		= clone $this->compositePath;
		$viewFactory 		= new ViewFactory($this->query);
		if($includeQueryInput)
		{
			$paginator  = $viewFactory->paginator($formCallback);
			$selectForm = $viewFactory->selectForm($columnNames, $formCallback);
		}
		$recordList = $viewFactory->recordList($columnSet, $rowSet);
		$addButton  = $viewFactory->addButton($compositePath, $rowCallback)
		;
		$view = new View('record-table')
			->add('addButton', $addButton)
			->add('recordList', $recordList)
			->add('filterSelector', $selectForm ?? '')
			->add('paginator', $paginator ?? '')
			->add('feedback', $this->feedback ?? '')
			;
		$this->strategy?->configureView(
			$view, $this->rowSetFactory, $this->collationMap);
		return $view;
	}

	private function generateRecordListRowSet():RowSet
	{
		$rowSetFactory = $this->rowSetFactory;
		$rowIds		   = $this->collationMap->getPaginatedIds();
		return $rowSetFactory->makeRowSet($rowIds);
	}

	public function setStrategy(?RecordTableStrategy $strategy): self
	{
		$this->strategy = $strategy;
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

	public function setCallbackInputForRows(
		Input $callbackInputForRows): self
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

	public function setQuery(Query $query): self
	{
		$this->query = $query;
		return $this;
	}
}