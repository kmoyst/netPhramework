<?php

namespace netPhramework\db\presentation\recordTable\FilterForm;

use netPhramework\db\presentation\recordTable\FilterKey;

class FilterFormDirector
{
	private FilterFormContext $context;
	private FilterFormInputFactory $factory;
	private FilterFormBuilder $builder;

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

	public function setBuilder(FilterFormBuilder $builder): self
	{
		$this->builder = $builder;
		return $this;
	}

	public function buildFilterForm():void
	{
		$this->addSortInputs();
		$this->addLimitInput();
		$this->addOffsetInput();
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
}