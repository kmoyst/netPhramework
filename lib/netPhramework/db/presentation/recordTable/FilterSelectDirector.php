<?php

namespace netPhramework\db\presentation\recordTable;

use netPhramework\db\presentation\recordTable\FilterForm\FilterFormBuilder;
use netPhramework\db\presentation\recordTable\FilterForm\FilterFormContext;
use netPhramework\db\presentation\recordTable\FilterForm\FilterFormDirector;
use netPhramework\db\presentation\recordTable\FilterForm\FilterFormInputFactory;
use netPhramework\rendering\View;

class FilterSelectDirector
{
	private FilterFormDirector $director;
	private FilterFormBuilder $builder;
	private FilterFormInputFactory $factory;
	private View $form;

	public function setDirector(FilterFormDirector $director): self
	{
		$this->director = $director;
		return $this;
	}

	public function setBuilder(FilterFormBuilder $builder): self
	{
		$this->builder = $builder;
		return $this;
	}

	public function setFactory(FilterFormInputFactory $factory): self
	{
		$this->factory = $factory;
		return $this;
	}

	public function buildForm(FilterFormContext $context):self
	{
		$this->director
			->setBuilder($this->builder)
			->setFactory($this->factory)
			->setContext($context)
			->buildFilterForm()
			;
		$this->form = $this->builder->getFilterForm('filter-select');
		return $this;
	}

	public function getView():View
	{
		return $this->form;
	}
}