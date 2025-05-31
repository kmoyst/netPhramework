<?php

namespace netPhramework\db\presentation\recordTable;

use netPhramework\presentation\Input;
use netPhramework\rendering\View;

class SelectFilterDirector
{
	private FilterFormInputFactory $factory;
	private View $form;
	private ?Input $callbackInput;

	public function __construct()
	{
		$this->factory = new SelectFilterFormInputFactory();
	}

	public function setColumnNames(array $columnNames):self
	{
		$this->factory->setColumnNames($columnNames);
		return $this;
	}

	public function setCallbackInput(?Input $callbackInput): self
	{
		$this->callbackInput = $callbackInput;
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
			->add('hiddenLimitInput', $builder->getHiddenLimitInput())
			->add('callbackInput', $this->callbackInput ?? '')
			;
		return $this;
	}

	public function getView():View
	{
		return $this->form;
	}
}