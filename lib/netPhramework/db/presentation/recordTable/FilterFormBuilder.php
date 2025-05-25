<?php

namespace netPhramework\db\presentation\recordTable;

use netPhramework\presentation\FormInput\Input;
use netPhramework\presentation\FormInput\InputSet;
use netPhramework\rendering\View;

class FilterFormBuilder
{
	private FilterFormContext $context;
	private FilterFormInputFactory $factory;
	private Input $limitInput;
	private Input $offsetInput;
	private InputSet $sortFieldInputs;
	private InputSet $sortDirectionInputs;
	private InputSet $conditionFieldInputs;
	private InputSet $conditionOperatorInputs;
	private InputSet $conditionValueInputs;
	private InputSet $conditionGlueInputs;

	public function __construct()
	{
		$this->sortFieldInputs = new InputSet();
		$this->sortDirectionInputs = new InputSet();
		$this->conditionFieldInputs = new InputSet();
		$this->conditionOperatorInputs = new InputSet();
		$this->conditionValueInputs = new InputSet();
		$this->conditionGlueInputs = new InputSet();
	}

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

	public function buildSortInputs():self
	{
		$i = 0;
		foreach($this->context->getSortArray() as $vector)
		{
			if($vector[FilterKey::SORT_FIELD->value] === '') break;
			$this->sortFieldInputs->addCustom($this->factory
				->makeSortFieldInput($i)
				->setValue($vector[FilterKey::SORT_FIELD->value])
			);
			$this->sortDirectionInputs->addCustom($this->factory
				->makeSortDirectionInput($i)
				->setValue($vector[FilterKey::SORT_DIRECTION->value])
			);
			$i++;
		}
		$this->sortFieldInputs->addCustom($this->factory
			->makeSortFieldInput($i)
		);
		$this->sortDirectionInputs->addCustom($this->factory
			->makeSortDirectionInput($i)
		);
		return $this;
	}

	public function buildLimitInput():self
	{
		$input = $this->factory->makeLimitInput();
		$input->setValue($this->context->getLimit());
		$this->limitInput = $input;
		return $this;
	}

	public function buildOffsetInput():self
	{
		$input = $this->factory->makeOffsetInput();
		$input->setValue($this->context->getOffset());
		$this->offsetInput = $input;
		return $this;
	}

	public function buildConditionInputs():self
	{
		$i = 0;
		foreach($this->context->getConditionSet() as $condition)
		{
			if($condition[FilterKey::CONDITION_FIELD->value] === '') break;
			$this->conditionFieldInputs->addCustom($this->factory
				->makeConditionFieldInput($i)
				->setValue($condition[FilterKey::CONDITION_FIELD->value]));

			$this->conditionOperatorInputs->addCustom($this->factory
				->makeConditionOperatorInput($i)
				->setValue($condition[FilterKey::CONDITION_OPERATOR->value])
			);
			$this->conditionValueInputs->addCustom($this->factory
				->makeConditionValueInput($i)
				->setValue($condition[FilterKey::CONDITION_VALUE->value])
			);
			$this->conditionGlueInputs->addCustom($this->factory
				->makeConditionGlueInput($i)
				->setValue($condition[FilterKey::CONDITION_GLUE->value])
			);
			$i++;
		}
		$this->conditionFieldInputs->addCustom(
			$this->factory->makeConditionFieldInput($i)
		);
		$this->conditionOperatorInputs->addCustom(
			$this->factory->makeConditionOperatorInput($i)
		);
		$this->conditionValueInputs->addCustom(
			$this->factory->makeConditionValueInput($i)
		);
		$this->conditionGlueInputs->addCustom(
			$this->factory->makeConditionGlueInput($i)
		);
		return $this;
	}

	public function createFilterForm(string $templateName):View
	{
		return new View($templateName)
			->add('conditionFieldInputs', $this->conditionFieldInputs)
			->add('conditionOperatorInputs', $this->conditionOperatorInputs)
			->add('conditionValueInputs', $this->conditionValueInputs)
			->add('conditionGlueInputs', $this->conditionGlueInputs)
			->add('sortFieldInputs', $this->sortFieldInputs)
			->add('sortDirectionInputs', $this->sortDirectionInputs)
			->add('limitInput', $this->limitInput)
			->add('offsetInput', $this->offsetInput)
			;
	}
}