<?php

namespace netPhramework\db\presentation\recordTable;

use netPhramework\db\presentation\recordTable\collation\QueryKey;
use netPhramework\db\presentation\recordTable\form\FormCondition;
use netPhramework\db\presentation\recordTable\form\FormSortVector;
use netPhramework\presentation\HiddenInput;
use netPhramework\presentation\Input;
use netPhramework\rendering\View;

class FormBuilder
{
	private FormContext $context;
	private FormInputFactory $factory;
	private array $conditionViews = [];
	private array $sortViews = [];
	private Input $limitInput;
	private Input $offsetInput;

	public function setContext(FormContext $context): self
	{
		$this->context = $context;
		return $this;
	}

	public function setFactory(FormInputFactory $factory): self
	{
		$this->factory = $factory;
		return $this;
	}

	public function buildSortInputs(string $templateName):self
	{
		$i = 0;
		foreach($this->context->getSortArray() as $vector)
		{
			$view = new FormSortVector($templateName);
			if($vector[QueryKey::SORT_FIELD->value] === '') break;
			$view->setFieldInput($this->factory
				->makeSortFieldInput($i)
				->setValue($vector[QueryKey::SORT_FIELD->value]));
			$view->setDirectionInput($this->factory
				->makeSortDirectionInput($i)
				->setValue($vector[QueryKey::SORT_DIRECTION->value]))
			;
			$this->sortViews[] = $view;
			$i++;
		}
		$view = new FormSortVector($templateName);
		$view->setFieldInput($this->factory->makeSortFieldInput($i));
		$view->setDirectionInput($this->factory->makeSortDirectionInput($i));
		$this->sortViews[] = $view;
		return $this;
	}

	public function buildLimitInput():self
	{
		$input = $this->factory->makeLimitInput();
		$input->setValue($this->context->getLimit());
		$this->limitInput = $input;
		return $this;
	}

	public function getHiddenLimitInput():HiddenInput
	{
		return new HiddenInput(
			QueryKey::LIMIT->value, $this->context->getLimit());
	}

	public function buildOffsetInput():self
	{
		$input = $this->factory->makeOffsetInput();
		$input->setValue($this->context->getOffset());
		$this->offsetInput = $input;
		return $this;
	}

	public function buildConditionInputs(string $templateName):self
	{
		$i = 0;
		foreach($this->context->getConditionSet() as $condition)
		{
			if(
				$condition[QueryKey::CONDITION_FIELD->value] == '' ||
				$condition[QueryKey::CONDITION_VALUE->value] == ''
			)
				continue;
			$view = new FormCondition($templateName)
			;
			$view->setGlueInput($this->factory
				->makeConditionGlueInput($i)
				->setValue($condition[QueryKey::CONDITION_GLUE->value] ?? ''))
			;
			$view->setFieldInput($this->factory
				->makeConditionFieldInput($i)
				->setValue($condition[QueryKey::CONDITION_FIELD->value]))
			;
			$view->setOperatorInput($this->factory
				->makeConditionOperatorInput($i)
				->setValue($condition[QueryKey::CONDITION_OPERATOR->value]))
			;
			$view->setValueInput($this->factory
				->makeConditionValueInput($i)
				->setValue($condition[QueryKey::CONDITION_VALUE->value]))
			;
			$this->conditionViews[] = $view;
			$i++;
		}
		$view = new FormCondition($templateName);
		$view->setGlueInput($this->factory->makeConditionGlueInput($i));
		$view->setFieldInput($this->factory->makeConditionFieldInput($i));
		$view->setOperatorInput($this->factory->makeConditionOperatorInput($i));
		$view->setValueInput($this->factory->makeConditionValueInput($i));
		$this->conditionViews[] = $view;
		$this->conditionViews[0]->setGlueInput(null);
		return $this;
	}

	public function getConditionViews(): array
	{
		return $this->conditionViews;
	}

	public function getSortViews(): array
	{
		return $this->sortViews;
	}

	public function getLimitInput(): Input
	{
		return $this->limitInput;
	}

	public function getOffsetInput(): Input
	{
		return $this->offsetInput;
	}

	public function createFilterForm(string $templateName):View
	{
		return new View($templateName)
			->add('conditions', $this->conditionViews)
			->add('sortArray', $this->sortViews)
			->add('limitInput', $this->limitInput)
			->add('offsetInput', $this->offsetInput)
			;
	}
}