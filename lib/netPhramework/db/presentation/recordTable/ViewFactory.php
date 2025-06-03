<?php

namespace netPhramework\db\presentation\recordTable;

use netPhramework\db\presentation\recordTable\columnSet\ColumnSet;
use netPhramework\db\presentation\recordTable\query\Query;
use netPhramework\db\presentation\recordTable\rowSet\RowSet;
use netPhramework\locating\MutablePath;
use netPhramework\presentation\Input;
use netPhramework\rendering\View;

readonly class ViewFactory
{
	public function __construct(private Query $query) { }

	public function getSelectForm(array $columnNames, ?Input $callbackInput):?View
	{
		return new QueryFormDirector()
			->setCallbackInput($callbackInput)
			->setColumnNames($columnNames)
			->buildSelectFilterForm($this->query)
			->getView()
		;
	}

	public function getPaginator(?Input $callbackInput):?View
	{
		if($this->query->hasLimit())
		{
			return new PaginationDirector()
				->configure($this->query, $callbackInput)
				->buildPreviousForm()
				->buildNextForm()
				->getView()
			;
		} else return null;
	}

	public function getAddButton(
		MutablePath $compositePath, Input $callbackInput):View
	{
		return new View('add-button-form')
			->add('callbackInput', $callbackInput)
			->add('action', $compositePath->append('add'))
		;
	}

	/**
	 * @param ColumnSet $columnSet
	 * @param RowSet $rowSet
	 * @return View
	 */
	public function getRecordList(ColumnSet $columnSet, RowSet $rowSet):View
	{
		return new View('record-list')
			->add('headers', $columnSet->getHeaders())
			->add('rows', 	 $rowSet)
			;
	}
}