<?php

namespace netPhramework\db\presentation\recordTable;

use netPhramework\core\Exception;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\RecordNotFound;
use netPhramework\db\exceptions\ValueInaccessible;
use netPhramework\db\mapping\RecordSet;
use netPhramework\db\presentation\recordTable\{columnSet\ColumnMapper,
	columnSet\ColumnSet,
	query\Query,
	rowSet\CollationMap,
	rowSet\CollationMapper,
	rowSet\RowSet,
	rowSet\RowSetFactory};
use netPhramework\locating\MutablePath;
use netPhramework\presentation\Input;
use netPhramework\rendering\Encodable;
use netPhramework\rendering\View;

class RecordTableBuilder
{
	protected RecordSet $recordSet;
	protected MutablePath $compositePath;
	protected Input $callbackInputForRows;
	protected ColumnSet $columnSet;
	protected Query $query;

	protected ?Input $callbackInputForFilterForms = null;
	protected ?Encodable $feedback = null;

	protected CollationMap $collationMap;
	protected RowSetFactory $rowSetFactory;

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
	public function collateRowSet():self
	{
		$rowSet = $this->rowSetFactory->makeRowSet($this->recordSet->getIds());
		$this->collationMap = new CollationMapper()
			->setRowSet($rowSet)
			->setQuery($this->query)
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
		return new View('record-table')
			->add('addButton', $addButton)
			->add('recordList', $recordList)
			->add('filterSelector', $selectForm ?? '')
			->add('paginator', $paginator ?? '')
			->add('feedback', $this->feedback ?? '')
			;
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

	public function setQuery(Query $query): self
	{
		$this->query = $query;
		return $this;
	}
}