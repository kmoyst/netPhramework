<?php

namespace netPhramework\db\presentation\recordTable;

use netPhramework\db\presentation\recordTable\columnSet\ColumnSet;
use netPhramework\db\presentation\recordTable\collation\Query;
use netPhramework\db\presentation\recordTable\rowSet\RowSet;
use netPhramework\routing\MutablePath;
use netPhramework\presentation\Input;
use netPhramework\rendering\Encodable;
use netPhramework\rendering\View;

readonly class ViewFactory
{
	public function __construct(private Query $query) { }

	public function getSelectForm(array $columnNames, ?Encodable $callbackInput):?View
	{
		return new QueryFormDirector()
			->setCallbackInput($callbackInput)
			->setColumnNames($columnNames)
			->buildSelectFilterForm($this->query)
			->getView()
		;
	}

	public function getPaginator(?Encodable $callbackInput):?View
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

	/**
	 * @param MutablePath $compositePath
	 * @param Encodable $callbackInput
	 * @return View
	 */
	public function getAddButton(
		MutablePath $compositePath, Encodable $callbackInput):View
	{
		return new View('add-button-form')
			->add('callbackInput', $callbackInput)
			->add('action', $compositePath->appendName('add'))
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