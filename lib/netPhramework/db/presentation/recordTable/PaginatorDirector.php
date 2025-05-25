<?php

namespace netPhramework\db\presentation\recordTable;

use netPhramework\rendering\View;
use netPhramework\rendering\Viewable;

class PaginatorDirector
{
	private FilterFormInputFactory $factory;
	private PaginatorFormContext $context;
	private PaginatorCalculator $calculator;
	private Viewable $prevForm;
	private Viewable $nextForm;

	public function __construct(private readonly FilterFormDirector $director)
	{
		$this->context 		= new PaginatorFormContext();
		$this->calculator  	= new PaginatorCalculator();
		$this->factory 		= new PaginatorFormInputFactory();
	}

	public function configure(FilterFormContext $baseContext):self
	{
		$this->context->setBaseContext($baseContext);
		$this->calculator
			->setLimit($this->context->getLimit())
			->setCurrentOffset($this->context->getOffset())
			->setTotalCount($this->context->getCount())
		;
		return $this;
	}

	public function buildPreviousForm():self
	{
		$builder = new FilterFormBuilder()
			->setFactory($this->factory)
			->setContext($this->context
				->setOffset($this->calculator->previousOffset()))
		;
		$this->director
			->setBuilder($builder)
			->constructForm();
		$this->prevForm = $builder
			->createFilterForm('paginator-form')
				->add('buttonText', 'Previous')
				->add('formName', 'previousPage')
		;
		return $this;
	}

	public function buildNextForm():self
	{
		$builder = new FilterFormBuilder()
			->setFactory($this->factory)
			->setContext($this->context
				->setOffset($this->calculator->nextOffset()))
		;
		$this->director
			->setBuilder($builder)
			->constructForm();
		$this->nextForm = $builder
			->createFilterForm('paginator-form')
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