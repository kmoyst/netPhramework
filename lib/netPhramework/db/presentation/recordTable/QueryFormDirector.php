<?php

namespace netPhramework\db\presentation\recordTable;

use netPhramework\rendering\Encodable;
use netPhramework\rendering\View;

class QueryFormDirector
{
	private QueryFormInputFactory $factory;
	private View $form;
	private ?Encodable $callbackInput;

	public function __construct()
	{
		$this->factory = new QueryFormInputFactory();
	}

	public function setColumnNames(array $columnNames):self
	{
		$this->factory->setColumnNames($columnNames);
		return $this;
	}

	public function setCallbackInput(?Encodable $callbackInput): self
	{
		$this->callbackInput = $callbackInput;
		return $this;
	}

	public function buildSelectFilterForm(FormContext $context):self
	{
		$builder = new FormBuilder()
			->setFactory($this->factory)
			->setContext($context)
			->buildConditionInputs('query-form-condition')
			->buildSortInputs('query-form-sort-vector')
			->buildLimitInput()
			;
		$this->form = new View('query-form')
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