<?php

namespace netPhramework\data\presentation\recordTable;

use netPhramework\data\presentation\recordTable\collation\Calculator;
use netPhramework\data\presentation\recordTable\{PaginationFormDirector as formDirector};
use netPhramework\rendering\Encodable;
use netPhramework\rendering\View;
use netPhramework\rendering\Viewable;

class PaginationDirector
{
	private PaginationFormInputFactory $factory;
	private FormBuilder $builder;
	private formDirector $formDirector;
	private PaginationFormContext $context;
	private Calculator $calculator;
	private Viewable $prevForm;
	private Viewable $nextForm;

	public function __construct()
	{
		$this->builder		= new FormBuilder();
		$this->formDirector	= new formDirector();
		$this->context 		= new PaginationFormContext();
		$this->calculator  	= new Calculator();
		$this->factory 		= new PaginationFormInputFactory();
	}

	public function configure(FormContext $baseContext,
							  ?Encodable $callbackInput):self
	{
		$this->context->setBaseContext($baseContext);
		$this->calculator
			->setLimit($baseContext->getLimit())
			->setCurrentOffset($baseContext->getOffset())
			->setTotalCount($baseContext->getCount())
		;
		$this->formDirector->setBuilder(
			$this->builder
				->setContext($this->context)
				->setFactory($this->factory)
		)->setCallbackInput($callbackInput);
		return $this;
	}

	public function buildPreviousForm():self
	{
		$this->context->setOffset($this->calculator->previousOffset());
		$this->prevForm = $this->formDirector->createForm()
			->add('buttonText', 'Previous')
			->add('formName', 'previousPage')
		;
		return $this;
	}

	public function buildNextForm():self
	{
		$this->context->setOffset($this->calculator->nextOffset());
		$this->nextForm = $this->formDirector->createForm()
			->add('buttonText', 'Next')
			->add('formName', 'nextPage')
			;
		return $this;
	}

	public function getView():View
	{
		return new View('paginator')
			->add('firstRowNumber', $this->calculator->firstRowNumber())
			->add('lastRowNumber', $this->calculator->lastRowNumber())
			->add('rowCount', $this->context->getCount())
			->add('pageNumber', $this->calculator->pageNumber())
			->add('pageCount', $this->calculator->pageCount())
			->add('prevForm', $this->prevForm)
			->add('nextForm', $this->nextForm)
			;
	}
}