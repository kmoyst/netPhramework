<?php

namespace netPhramework\db\presentation\recordTable;

class FilterFormDirector
{
	private FilterFormBuilder $builder;
	private FilterFormStrategy $strategy;
	private FilterFormContext $context;

	public function __construct()
	{
		$this->builder = new FilterFormBuilder();
	}

	public function setStrategy(FilterFormStrategy $strategy): self
	{
		$this->strategy = $strategy;
		return $this;
	}

	public function setContext(FilterFormContext $context): self
	{
		$this->context = $context;
		return $this;
	}

	public function createForm():FilterForm
	{
		return $this->builder
			->newForm()
			->setStrategy($this->strategy)
			->setContext($this->context)
			->setFormName()
			->setTemplateName()
			->setButtonText()
			->addLimitInput()
			->addOffsetInput()
			->addSortFieldInput()
			->addSortDirectionInput()
			->getFilterForm()
			;
	}
}