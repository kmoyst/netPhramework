<?php

namespace netPhramework\db\presentation\recordTable;

use netPhramework\presentation\HiddenInput;
use netPhramework\presentation\Input;
use netPhramework\rendering\View;

class FilterFormBuilder
{
	private FilterFormContext $context;
	private FilterFormInputFactory $factory;
	private array $conditionViews = [];
	private array $sortViews = [];
	private Input $limitInput;
	private Input $offsetInput;

	public function setContext(FilterFormContext $context): self
	{
		$this->context = $context;
		return $this;
	}

	public function setFactory(FilterFormInputFactory $factory): self
	{
		$this->factory = $factory;
		return $this;
	}

	public function buildSortInputs(string $templateName):self
	{
		$i = 0;
		foreach($this->context->getSortArray() as $vector)
		{
			$view = new FilterFormSortVector($templateName);
			if($vector[FilterKey::SORT_FIELD->value] === '') break;
			$view->setFieldInput($this->factory
				->makeSortFieldInput($i)
				->setValue($vector[FilterKey::SORT_FIELD->value]));
			$view->setDirectionInput($this->factory
				->makeSortDirectionInput($i)
				->setValue($vector[FilterKey::SORT_DIRECTION->value]))
			;
			$this->sortViews[] = $view;
			$i++;
		}
		$view = new FilterFormSortVector($templateName);
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
			FilterKey::LIMIT->value, $this->context->getLimit());
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
				$condition[FilterKey::CONDITION_FIELD->value] == '' ||
				$condition[FilterKey::CONDITION_VALUE->value] == ''
			)
				continue;
			$view = new FilterFormCondition($templateName)
			;
			$view->setGlueInput($this->factory
				->makeConditionGlueInput($i)
				->setValue($condition[FilterKey::CONDITION_GLUE->value] ?? ''))
			;
			$view->setFieldInput($this->factory
				->makeConditionFieldInput($i)
				->setValue($condition[FilterKey::CONDITION_FIELD->value]))
			;
			$view->setOperatorInput($this->factory
				->makeConditionOperatorInput($i)
				->setValue($condition[FilterKey::CONDITION_OPERATOR->value]))
			;
			$view->setValueInput($this->factory
				->makeConditionValueInput($i)
				->setValue($condition[FilterKey::CONDITION_VALUE->value]))
			;
			$this->conditionViews[] = $view;
			$i++;
		}
		$view = new FilterFormCondition($templateName);
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