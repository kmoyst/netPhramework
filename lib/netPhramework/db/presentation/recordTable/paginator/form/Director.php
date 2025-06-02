<?php

namespace netPhramework\db\presentation\recordTable\paginator\form;

use netPhramework\db\presentation\recordTable\form\Builder;
use netPhramework\presentation\Input;
use netPhramework\rendering\View;

class Director
{
	private Builder $builder;
	private ?Input $callbackInput;

	public function setBuilder(Builder $builder): self
	{
		$this->builder = $builder;
		return $this;
	}

	public function setCallbackInput(?Input $callbackInput): self
	{
		$this->callbackInput = $callbackInput;
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
		return new View('paginator-form')
			->add('conditions', $this->builder->getConditionViews())
			->add('sortArray', $this->builder->getSortViews())
			->add('limitInput', $this->builder->getLimitInput())
			->add('offsetInput', $this->builder->getOffsetInput())
			->add('callbackInput', $this->callbackInput ?? '')
		;
	}
}