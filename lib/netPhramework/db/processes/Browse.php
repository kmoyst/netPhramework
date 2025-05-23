<?php

namespace netPhramework\db\processes;

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
use netPhramework\db\presentation\recordTable\FilterFormDirector;
use netPhramework\db\presentation\recordTable\FilterSelectFormStrategy;
use netPhramework\db\presentation\recordTable\PaginatorBuilder;
use netPhramework\db\presentation\recordTable\RowSetBuilder;
use netPhramework\exceptions\Exception;
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
		$filterFormDirector = new FilterFormDirector()
		;
		$filterSelectFormStrategy = new FilterSelectFormStrategy()
			->setColumnHeaders($columnSet->getHeaders())
		;
		$filterSelectForm = $filterFormDirector
			->setStrategy($filterSelectFormStrategy)
			->setContext($filterContext)
			->createForm()
		;
		$paginator = $filterContext->getLimit() === null ? '' :
			new PaginatorBuilder($filterContext, $filterFormDirector)
				->initCalculator()
				->buildNextForm()
				->buildPreviousForm()
				->getPaginator()
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
        $errorView    = $exchange->getSession()->getViewableError() ?? '';
        $responseCode = $exchange->getSession()->resolveResponseCode()
        ;
        $view = new View('browse');
		$view->getVariables()
			->add('filterSelectForm', 	$filterSelectForm)
			->add('addButtonForm', 		$addButtonForm)
			->add('recordTable', 		$recordTable)
			->add('paginator', 			$paginator)
            ->add('errorView',          $errorView)
		;
        $exchange->display($view->setTitle('Browse Records'), $responseCode);
	}
}