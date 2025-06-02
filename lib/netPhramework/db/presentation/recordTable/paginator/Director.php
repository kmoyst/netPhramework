<?php

namespace netPhramework\db\presentation\recordTable\paginator;

use netPhramework\db\presentation\recordTable\form\Builder;
use netPhramework\db\presentation\recordTable\
{
	paginator\form\Director as formDirector,
	paginator\form\InputFactory,
	query\QueryInterface as baseQuery
};
use netPhramework\presentation\Input;
use netPhramework\rendering\View;
use netPhramework\rendering\Viewable;

class Director
{
	private InputFactory $factory;
	private Builder $builder;
	private formDirector $formDirector;
	private Query $query;
	private Calculator $calculator;
	private Viewable $prevForm;
	private Viewable $nextForm;

	public function __construct()
	{
		$this->builder		= new Builder();
		$this->formDirector	= new formDirector();
		$this->query 		= new Query();
		$this->calculator  	= new Calculator();
		$this->factory 		= new InputFactory();
	}

	public function configure(baseQuery $baseQuery,
							  ?Input      $callbackInput):self
	{
		$this->query->setBaseQuery($baseQuery);
		$this->calculator
			->setLimit($baseQuery->getLimit())
			->setCurrentOffset($baseQuery->getOffset())
			->setTotalCount($baseQuery->getCount())
		;
		$this->formDirector->setBuilder(
			$this->builder
				->setQuery($this->query)
				->setFactory($this->factory)
		)->setCallbackInput($callbackInput);
		return $this;
	}

	public function buildPreviousForm():self
	{
		$this->query->setOffset($this->calculator->previousOffset());
		$this->prevForm = $this->formDirector->createForm()
			->add('buttonText', 'Previous')
			->add('formName', 'previousPage')
		;
		return $this;
	}

	public function buildNextForm():self
	{
		$this->query->setOffset($this->calculator->nextOffset());
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
			->add('rowCount', $this->query->getCount())
			->add('pageNumber', $this->calculator->pageNumber())
			->add('pageCount', $this->calculator->pageCount())
			->add('prevForm', $this->prevForm)
			->add('nextForm', $this->nextForm)
			;
	}
}