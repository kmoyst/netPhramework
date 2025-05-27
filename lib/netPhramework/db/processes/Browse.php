<?php

namespace netPhramework\db\processes;

use netPhramework\core\Exception;
use netPhramework\core\Exchange;
use netPhramework\db\core\RecordSetProcess;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\ValueInaccessible;
use netPhramework\db\presentation\recordTable\ColumnMapper;
use netPhramework\db\presentation\recordTable\ColumnSetBuilder;
use netPhramework\db\presentation\recordTable\ColumnStrategy;
use netPhramework\db\presentation\recordTable\FilterContext;
use netPhramework\db\presentation\recordTable\PaginatorDirector;
use netPhramework\db\presentation\recordTable\RowSetBuilder;
use netPhramework\db\presentation\recordTable\SelectFilterDirector;
use netPhramework\rendering\View;

class Browse extends RecordSetProcess
{
	public function __construct(
		private readonly ?ColumnStrategy $columnStrategy = null,
		?string $name = null,
		private readonly ?ColumnMapper   $columnMapper = null)
	{
		$this->name = $name;
	}

	/**
	 * @param Exchange $exchange
	 * @return void
	 * @throws MappingException
	 * @throws FieldAbsent
	 * @throws ValueInaccessible
	 * @throws Exception
	 */
	public function handleExchange(Exchange $exchange): void
	{
		$recordSet = $this->recordSet
		;
		$filterContext = new FilterContext()
			->setRecordSet($recordSet)
			->setVariables($exchange->getParameters())
			->parse()
		;
		$columnSet = new ColumnSetBuilder()
			->setMapper($this->columnMapper ?? new ColumnMapper())
			->setStrategy($this->columnStrategy)
			->setFieldSet($recordSet->getFieldSet())
			->getColumnSet()
		;
		$rowSetBuilder = new RowSetBuilder()
			->setRecordSet($recordSet)
			->setColumnSet($columnSet)
			->setContext($filterContext)
			->filter()
			->sort()
		;
		$filterSelectView   = new SelectFilterDirector()
			->configure($columnSet->getNames())
			->buildSelectFilterForm($filterContext)
			->getView()
		;
		$paginatorView =
			$filterContext->getLimit() === null ? '' :
				new PaginatorDirector()
			->configure($filterContext)
			->buildPreviousForm()
			->buildNextForm()
			->getView()
		;
		$callbackInput = $exchange->callbackFormInput()
		;
		$recordTable = new View('record-table')
			->add('headers', 		$columnSet->getHeaders())
			->add('rows', 			$rowSetBuilder->getRowSet())
			->add('callbackInput', 	$callbackInput)
			->add('actionPrefix', 	'')
		;
		$addButtonForm = new View('add-button-form');
		$addButtonForm->getVariables()->add('callbackInput', $callbackInput)
		;
        $errorView    = $exchange->getSession()->getEncodableValue() ?? '';
        $responseCode = $exchange->getSession()->resolveResponseCode()
        ;
		$exchange->display(new View('browse'), $responseCode)
			->setTitle('Browse Records')
			->add('filterSelectView', 	$filterSelectView)
			->add('paginator', 			$paginatorView)
			->add('addButtonForm', 		$addButtonForm)
			->add('recordTable', 		$recordTable)
			->add('errorView',          $errorView)
		;
	}
}