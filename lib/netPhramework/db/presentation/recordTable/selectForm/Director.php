<?php

namespace netPhramework\db\presentation\recordTable\selectForm;

use netPhramework\db\presentation\recordTable\query\QueryInterface;
use netPhramework\db\presentation\recordTable\form\Builder;
use netPhramework\presentation\Input;
use netPhramework\rendering\View;

class Director
{
	private InputFactory $factory;
	private View $form;
	private ?Input $callbackInput;

	public function __construct()
	{
		$this->factory = new InputFactory();
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

	public function buildSelectFilterForm(QueryInterface $query):self
	{
		$builder = new Builder()
			->setFactory($this->factory)
			->setQuery($query)
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