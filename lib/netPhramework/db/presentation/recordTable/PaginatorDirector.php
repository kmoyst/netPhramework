<?php

namespace netPhramework\db\presentation\recordTable;

use netPhramework\db\presentation\recordTable\FilterForm\FilterFormBuilder;
use netPhramework\db\presentation\recordTable\FilterForm\FilterFormContext;
use netPhramework\db\presentation\recordTable\FilterForm\FilterFormDirector;
use netPhramework\db\presentation\recordTable\FilterForm\FilterFormInputFactory;
use netPhramework\db\presentation\recordTable\PaginatorForm\PaginatorFormContext;
use netPhramework\rendering\View;
use netPhramework\rendering\Viewable;

class PaginatorDirector
{
	private FilterFormDirector $director;
	private FilterFormInputFactory $factory;
	private PaginatorFormContext $context;
	private PaginatorCalculator $calculator;
	private Viewable $prevForm;
	private Viewable $nextForm;

	public function setDirector(FilterFormDirector $director): self
	{
		$this->director = $director;
		return $this;
	}

	public function setFactory(FilterFormInputFactory $factory): self
	{
		$this->factory = $factory;
		return $this;
	}

	public function setContext(PaginatorFormContext $context): self
	{
		$this->context = $context;
		return $this;
	}

	public function setCalculator(PaginatorCalculator $calculator): self
	{
		$this->calculator = $calculator;
		return $this;
	}

	public function configureCalculator(FilterFormContext $context):self
	{
		$this->calculator->setLimit($context->getLimit());
		$this->calculator->setCurrentOffset($context->getOffset());
		$this->calculator->setTotalCount($context->getCount());
		return $this;
	}

	public function prepareDirector(FilterFormContext $context):self
	{
		$this->director
			->setContext($this->context->setBaseContext($context))
			->setFactory($this->factory)
		;
		return $this;
	}

	public function buildPreviousForm():self
	{
		$builder = new FilterFormBuilder();
		$this->context
			->setOffset($this->calculator->previousOffset());
		$this->director
			->setBuilder($builder)
			->buildFilterForm()
		;
		$this->prevForm = $builder
			->getFilterForm('paginator-form')
			->add('buttonText', 'Previous')
			->add('formName', 'previousPage')
		;
		return $this;
	}

	public function buildNextForm():self
	{
		$builder = new FilterFormBuilder();
		$this->context
			->setOffset($this->calculator->nextOffset());
		$this->director
			->setBuilder($builder)
			->buildFilterForm()
		;
		$this->nextForm = $builder
			->getFilterForm('paginator-form')
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