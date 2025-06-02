<?php

namespace netPhramework\db\presentation\recordTable\paginator;

use netPhramework\db\presentation\recordTable\filterForm\FilterFormBuilder;
use netPhramework\db\presentation\recordTable\filterForm\FilterFormContext;
use netPhramework\db\presentation\recordTable\filterForm\FilterFormInputFactory;
use netPhramework\presentation\Input;
use netPhramework\rendering\View;
use netPhramework\rendering\Viewable;

class PaginatorDirector
{
	private FilterFormInputFactory $factory;
	private FilterFormBuilder $builder;
	private PaginatorFormDirector $director;
	private PaginatorFormContext $context;
	private PaginatorCalculator $calculator;
	private Viewable $prevForm;
	private Viewable $nextForm;

	public function __construct()
	{
		$this->builder		= new FilterFormBuilder();
		$this->director		= new PaginatorFormDirector();
		$this->context 		= new PaginatorFormContext();
		$this->calculator  	= new PaginatorCalculator();
		$this->factory 		= new PaginatorFormInputFactory();
	}

	public function configure(FilterFormContext $baseContext,
							  ?Input $callbackInput):self
	{
		$this->context->setBaseContext($baseContext);
		$this->calculator
			->setLimit($baseContext->getLimit())
			->setCurrentOffset($baseContext->getOffset())
			->setTotalCount($baseContext->getCount())
		;
		$this->director->setBuilder(
			$this->builder
				->setContext($this->context)
				->setFactory($this->factory)
		)->setCallbackInput($callbackInput);
		return $this;
	}

	public function buildPreviousForm():self
	{
		$this->context->setOffset($this->calculator->previousOffset());
		$this->prevForm = $this->director->createForm()
			->add('buttonText', 'Previous')
			->add('formName', 'previousPage')
		;
		return $this;
	}

	public function buildNextForm():self
	{
		$this->context->setOffset($this->calculator->nextOffset());
		$this->nextForm = $this->director->createForm()
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