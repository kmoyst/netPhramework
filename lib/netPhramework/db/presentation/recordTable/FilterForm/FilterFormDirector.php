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
		$i = 0;
		foreach($this->context->getSortArray() as $vector)
		{
			if($vector[$fieldKey->value] === '') break;
			$this->builder->addSortFieldInput($this->factory
				->makeSortFieldInput($fieldKey, $parentKey, $i)
				->setValue($vector[$fieldKey->value])
			);
			$this->builder->addSortDirectionInput($this->factory
				->makeSortDirectionInput($directionKey, $parentKey, $i)
				->setValue($vector[$directionKey->value])
			);
			$i++;
		}
		$this->builder->addSortFieldInput($this->factory
			->makeSortFieldInput($fieldKey, $parentKey, $i)
		);
		$this->builder->addSortDirectionInput($this->factory
			->makeSortDirectionInput($directionKey, $parentKey, $i)
		);
		return $this;
	}

	public function createForm():void
	{
		$this
			->addSortInputs(
				FilterKey::SORT_ARRAY,
				FilterKey::SORT_FIELD,
				FilterKey::SORT_DIRECTION)
			->addLimitInput(FilterKey::LIMIT)
			->addOffsetInput(FilterKey::OFFSET)
			;
	}
}