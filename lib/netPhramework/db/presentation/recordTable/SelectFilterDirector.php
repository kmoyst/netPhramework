<?php

namespace netPhramework\db\presentation\recordTable;

use netPhramework\rendering\View;

class SelectFilterDirector
{
	private FilterFormInputFactory $factory;
	private View $form;

	public function __construct()
	{
		$this->factory = new SelectFilterFormInputFactory();
	}

	public function configure(array $columnNames):self
	{
		$this->factory->setColumnNames($columnNames);
		return $this;
	}

	public function buildSelectFilterForm(FilterFormContext $context):self
	{
		$builder = new FilterFormBuilder()
			->setFactory($this->factory)
			->setContext($context)
			->buildConditionInputs('select-filter-condition')
			->buildSortInputs('select-filter-sort-vector')
			->buildLimitInput()
			;
		$this->form = new View('select-filter')
			->add('conditions', $builder->getConditionViews())
			->add('sortArray', $builder->getSortViews())
			->add('limitInput', $builder->getLimitInput())
			;
		return $this;
	}

	public function getView():View
	{
		return $this->form;
	}
}