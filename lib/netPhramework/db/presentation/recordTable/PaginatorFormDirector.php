<?php

namespace netPhramework\db\presentation\recordTable;

use netPhramework\rendering\View;

class PaginatorFormDirector
{
	private FilterFormBuilder $builder;

	public function setBuilder(FilterFormBuilder $builder): self
	{
		$this->builder = $builder;
		return $this;
	}

	public function createForm():View
	{
		$this->builder
			->buildConditionInputs('paginator-condition')
			->buildSortInputs('paginator-sort-vector')
			->buildLimitInput()
			->buildOffsetInput()
		;
		$inputViews = array_merge(
			$this->builder->getConditionViews(),
			$this->builder->getSortViews());
		$inputViews[] = $this->builder->getLimitInput();
		$inputViews[] = $this->builder->getOffsetInput();
		return new View('paginator-form')
			->add('inputs', $inputViews)
		;
	}
}