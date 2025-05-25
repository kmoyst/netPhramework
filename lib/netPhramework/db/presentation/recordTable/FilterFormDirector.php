<?php

namespace netPhramework\db\presentation\recordTable;

class FilterFormDirector
{
	private FilterFormContext $context;
	private FilterFormInputFactory $factory;
	private FilterFormBuilder $builder;

	public function setBuilder(FilterFormBuilder $builder): self
	{
		$this->builder = $builder;
		return $this;
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

	private function addSortInputs():void
	{
		$i = 0;
		foreach($this->context->getSortArray() as $vector)
		{
			if($vector[FilterKey::SORT_FIELD->value] === '') break;
			$this->builder->addSortFieldInput($this->factory
				->makeSortFieldInput($i)
				->setValue($vector[FilterKey::SORT_FIELD->value])
			);
			$this->builder->addSortDirectionInput($this->factory
				->makeSortDirectionInput($i)
				->setValue($vector[FilterKey::SORT_DIRECTION->value])
			);
			$i++;
		}
		$this->builder->addSortFieldInput($this->factory
			->makeSortFieldInput($i)
		);
		$this->builder->addSortDirectionInput($this->factory
			->makeSortDirectionInput($i)
		);
	}

	private function addLimitInput():void
	{
		$input = $this->factory->makeLimitInput();
		$input->setValue($this->context->getLimit());
		$this->builder->addLimitInput($input);
	}

	private function addOffsetInput():void
	{
		$input = $this->factory->makeOffsetInput();
		$input->setValue($this->context->getOffset());
		$this->builder->addOffsetInput($input);
	}

	private function addConditionInputs():void
	{
		$i = 0;
		foreach($this->context->getConditionSet() as $condition)
		{
			if($condition[FilterKey::CONDITION_FIELD->value] === '') break;
			$this->builder->addConditionFieldInput($this->factory
				->makeConditionFieldInput($i)
				->setValue($condition[FilterKey::CONDITION_FIELD->value])
			);
			$this->builder->addConditionOperatorInput($this->factory
				->makeConditionOperatorInput($i)
				->setValue($condition[FilterKey::CONDITION_OPERATOR->value])
			);
			$this->builder->addConditionValueInput($this->factory
				->makeConditionValueInput($i)
				->setValue($condition[FilterKey::CONDITION_VALUE->value])
			);
			$this->builder->addCondtionGlueInput($this->factory
				->makeConditionGlueInput($i)
				->setValue($condition[FilterKey::CONDITION_GLUE->value])
			);
			$i++;
		}
		$this->builder->addConditionFieldInput(
			$this->factory->makeConditionFieldInput($i)
		);
		$this->builder->addConditionOperatorInput(
			$this->factory->makeConditionOperatorInput($i)
		);
		$this->builder->addConditionValueInput(
			$this->factory->makeConditionValueInput($i)
		);
		$this->builder->addCondtionGlueInput(
			$this->factory->makeConditionGlueInput($i)
		);
	}

	public function buildFilterForm():void
	{
		$this->addConditionInputs();
		$this->addSortInputs();
		$this->addLimitInput();
		$this->addOffsetInput();
	}
}