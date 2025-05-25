<?php

namespace netPhramework\db\processes;

use netPhramework\core\Exception;
use netPhramework\core\Exchange;
use netPhramework\db\core\RecordSet;
use netPhramework\db\core\RecordSetProcess;
use netPhramework\db\exceptions\FieldAbsent;
use netPhramework\db\exceptions\MappingException;
use netPhramework\db\exceptions\ValueInaccessible;
use netPhramework\db\presentation\recordTable\ColumnMapper;
use netPhramework\db\presentation\recordTable\ColumnSetBuilder;
use netPhramework\db\presentation\recordTable\ColumnStrategy;
use netPhramework\db\presentation\recordTable\FilterContext;
use netPhramework\db\presentation\recordTable\FilterForm\FilterFormBuilder;
use netPhramework\db\presentation\recordTable\FilterForm\FilterFormDirector;
use netPhramework\db\presentation\recordTable\FilterSelectDirector;
use netPhramework\db\presentation\recordTable\FilterSelectForm\FilterSelectFormInputFactory;
use netPhramework\db\presentation\recordTable\PaginatorCalculator;
use netPhramework\db\presentation\recordTable\PaginatorDirector;
use netPhramework\db\presentation\recordTable\PaginatorForm\PaginatorFormContext;
use netPhramework\db\presentation\recordTable\PaginatorForm\PaginatorFormInputFactory;
use netPhramework\db\presentation\recordTable\RowSetBuilder;
use netPhramework\rendering\View;

class Browse extends RecordSetProcess
{
	public function __construct(
		private readonly ?ColumnStrategy $columnStrategy = null,
		?string $name = null,
		private readonly ?ColumnMapper   $columnMapper = null)
	{
		parent::__construct($name);
	}

	/**
	 * @param Exchange $exchange
	 * @param RecordSet $recordSet
	 * @return void
	 * @throws MappingException
	 * @throws FieldAbsent
	 * @throws ValueInaccessible
	 * @throws Exception
	 */
	public function handleExchange(
		Exchange $exchange, RecordSet $recordSet): void
	{
		$filterContext = new FilterContext()
			->setRecordSet($recordSet)
			->setVariables($exchange->getParameters())
			->parse()
		;
		$columnSet = new ColumnSetBuilder()
			->setMapper($this->columnMapper ?? new ColumnMapper())
			->setStrategy($this->columnStrategy)
			->setFieldSet($recordSet->getFieldSet())
			->build()
		;
		$rowSetBuilder = new RowSetBuilder()
			->setRecordSet($recordSet)
			->setColumnSet($columnSet)
			->setContext($filterContext)
			->sort()
		;
		$filterFormDirector 			= new FilterFormDirector();
		$filterFormBuilder 				= new FilterFormBuilder();
		$filterSelectFormInputFactory 	= new FilterSelectFormInputFactory()
			->setColumnHeaders($columnSet->getHeaders())
		;
		$filterSelectView = new FilterSelectDirector()
			->setBuilder($filterFormBuilder)
			->setDirector($filterFormDirector)
			->setFactory($filterSelectFormInputFactory)
			->buildForm($filterContext)
			->getView()
			;
		$paginatorView = $filterContext->getLimit() === null ? '' :
			new PaginatorDirector()
				->setDirector($filterFormDirector)
				->setContext(new PaginatorFormContext())
				->setCalculator(new PaginatorCalculator())
				->setFactory(new PaginatorFormInputFactory())
				->configureCalculator($filterContext)
				->prepareDirector($filterContext)
				->buildNextForm()
				->buildPreviousForm()
				->getView()
		;
		$callbackInput = $exchange->callbackFormInput()
		;
		$recordTable = new View('record-table');
		$recordTable->getVariables()
			->add('headers', 		$columnSet->getHeaders())
			->add('rows', 			$rowSetBuilder->getRowSet())
			->add('callbackInput', 	$callbackInput)
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