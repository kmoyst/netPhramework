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

	public function addLimitInput(FilterKey $limitKey):self
	{
		$input = $this->factory->makeLimitInput($limitKey);
		$input->setValue($this->context->getLimit());
		$this->builder->addLimitInput($input);
		return $this;
	}

	public function addOffsetInput(FilterKey $offsetKey):self
	{
		$input = $this->factory->makeOffsetInput($offsetKey);
		$input->setValue($this->context->getOffset());
		$this->builder->addOffsetInput($input);
		return $this;
	}

	public function addSortInputs(FilterKey $parentKey,
		FilterKey $fieldKey, FilterKey $directionKey):self
	{
		foreach($this->context->getSortArray() as $i => $vector)
		{
			$this->builder->addSortFieldInput($this->factory
				->makeSortFieldInput($fieldKey, $parentKey, $i)
				->setValue($vector[$fieldKey->value])
			);
			$this->builder->addSortDirectionInput($this->factory
				->makeSortDirectionInput($directionKey, $parentKey, $i)
				->setValue($vector[$directionKey->value])
			);
		}
		$this->builder->addSortFieldInput($this->factory
			->makeSortFieldInput($fieldKey, $parentKey, 0)
		);
		$this->builder->addSortDirectionInput($this->factory
			->makeSortDirectionInput($directionKey, $parentKey, 0)
		);
		return $this;
	}
}