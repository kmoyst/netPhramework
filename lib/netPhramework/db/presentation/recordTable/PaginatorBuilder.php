<?php

namespace netPhramework\db\presentation\recordTable;

use netPhramework\common\Variables;
use netPhramework\rendering\ReadonlyView;

class PaginatorBuilder
{
	private PaginatorCalculator $calculator;
	private FilterForm $previousForm;
	private FilterForm $nextForm;

	public function __construct(
		private readonly FilterFormContext $baseContext,
		private readonly FilterFormDirector $formDirector) {}

	public function initCalculator():PaginatorBuilder
	{
		$context = $this->baseContext;
		$this->calculator = new PaginatorCalculator(
			$context->getLimit(), $context->getOffset(), $context->getCount());
		return $this;
	}

	public function buildPreviousForm():PaginatorBuilder
	{
		$strategy = new PaginatorFormStrategy('previousPageForm', 'Previous');
		$context  = new PaginatorContext($this->baseContext);
		$context->setOffset($this->calculator->previousOffset());
		$this->previousForm = $this->formDirector
			->setStrategy($strategy)
			->setContext($context)
			->createForm();
		return $this;
	}

	public function buildNextForm():PaginatorBuilder
	{
		$strategy = new PaginatorFormStrategy('nextPageForm', 'Next');
		$context  = new PaginatorContext($this->baseContext);
		$context->setOffset($this->calculator->nextOffset());
		$this->nextForm = $this->formDirector
			->setStrategy($strategy)
			->setContext($context)
			->createForm();
		return $this;
	}

	public function getPaginator():ReadonlyView
	{
		$variables = new Variables();
		$variables
			->add('firstRowNumber', $this->calculator->firstRowNumber())
			->add('lastRowNumber', $this->calculator->lastRowNumber())
			->add('pageCount', $this->calculator->pageCount())
			->add('pageNumber', $this->calculator->pageNumber())
			->add('rowCount', $this->baseContext->getCount())
			->add('previousForm', $this->previousForm)
			->add('nextForm', $this->nextForm)
			;
		return new ReadonlyView('paginator', $variables);
	}
}