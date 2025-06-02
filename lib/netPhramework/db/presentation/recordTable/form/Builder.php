<?php

namespace netPhramework\db\presentation\recordTable\form;

use netPhramework\db\presentation\recordTable\query\Condition;
use netPhramework\db\presentation\recordTable\query\Key;
use netPhramework\db\presentation\recordTable\query\QueryInterface;
use netPhramework\db\presentation\recordTable\query\SortVector;
use netPhramework\presentation\HiddenInput;
use netPhramework\presentation\Input;
use netPhramework\rendering\View;

class Builder
{
	private QueryInterface $query;
	private InputFactory $factory;
	private array $conditionViews = [];
	private array $sortViews = [];
	private Input $limitInput;
	private Input $offsetInput;

	public function setQuery(QueryInterface $query): self
	{
		$this->query = $query;
		return $this;
	}

	public function setFactory(InputFactory $factory): self
	{
		$this->factory = $factory;
		return $this;
	}

	public function buildSortInputs(string $templateName):self
	{
		$i = 0;
		foreach($this->query->getSortArray() as $vector)
		{
			$view = new SortVector($templateName);
			if($vector[Key::SORT_FIELD->value] === '') break;
			$view->setFieldInput($this->factory
				->makeSortFieldInput($i)
				->setValue($vector[Key::SORT_FIELD->value]));
			$view->setDirectionInput($this->factory
				->makeSortDirectionInput($i)
				->setValue($vector[Key::SORT_DIRECTION->value]))
			;
			$this->sortViews[] = $view;
			$i++;
		}
		$view = new SortVector($templateName);
		$view->setFieldInput($this->factory->makeSortFieldInput($i));
		$view->setDirectionInput($this->factory->makeSortDirectionInput($i));
		$this->sortViews[] = $view;
		return $this;
	}

	public function buildLimitInput():self
	{
		$input = $this->factory->makeLimitInput();
		$input->setValue($this->query->getLimit());
		$this->limitInput = $input;
		return $this;
	}

	public function getHiddenLimitInput():HiddenInput
	{
		return new HiddenInput(
			Key::LIMIT->value, $this->query->getLimit());
	}

	public function buildOffsetInput():self
	{
		$input = $this->factory->makeOffsetInput();
		$input->setValue($this->query->getOffset());
		$this->offsetInput = $input;
		return $this;
	}

	public function buildConditionInputs(string $templateName):self
	{
		$i = 0;
		foreach($this->query->getConditionSet() as $condition)
		{
			if(
				$condition[Key::CONDITION_FIELD->value] == '' ||
				$condition[Key::CONDITION_VALUE->value] == ''
			)
				continue;
			$view = new Condition($templateName)
			;
			$view->setGlueInput($this->factory
				->makeConditionGlueInput($i)
				->setValue($condition[Key::CONDITION_GLUE->value] ?? ''))
			;
			$view->setFieldInput($this->factory
				->makeConditionFieldInput($i)
				->setValue($condition[Key::CONDITION_FIELD->value]))
			;
			$view->setOperatorInput($this->factory
				->makeConditionOperatorInput($i)
				->setValue($condition[Key::CONDITION_OPERATOR->value]))
			;
			$view->setValueInput($this->factory
				->makeConditionValueInput($i)
				->setValue($condition[Key::CONDITION_VALUE->value]))
			;
			$this->conditionViews[] = $view;
			$i++;
		}
		$view = new Condition($templateName);
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