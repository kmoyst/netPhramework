<?php

namespace netPhramework\db\presentation\recordTable;

class FilterFormDirector
{
	private FilterFormBuilder $builder;

	public function setBuilder(FilterFormBuilder $builder): self
	{
		$this->builder = $builder;
		return $this;
	}

	public function constructForm():void
	{
		$this->builder
			->buildConditionInputs()
			->buildSortInputs()
			->buildLimitInput()
			->buildOffsetInput()
		;
	}
}