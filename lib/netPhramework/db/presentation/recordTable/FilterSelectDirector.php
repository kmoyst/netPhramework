<?php

namespace netPhramework\db\presentation\recordTable;

use netPhramework\db\presentation\recordTable\FilterForm\FilterFormBuilder;
use netPhramework\db\presentation\recordTable\FilterForm\FilterFormContext;
use netPhramework\db\presentation\recordTable\FilterForm\FilterFormDirector;
use netPhramework\db\presentation\recordTable\FilterForm\FilterFormInputFactory;
use netPhramework\rendering\View;
use netPhramework\rendering\Viewable;

class FilterSelectDirector
{
	private FilterFormDirector $director;
	private FilterFormBuilder $builder;
	private FilterFormInputFactory $factory;

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

	public function buildForm(FilterFormContext $context):Viewable
	{
		$this->director
			->setBuilder($this->builder)
			->setFactory($this->factory)
			->setContext($context)
			->createForm()
			;
		return $this->builder->buildFilterForm('filter-select');
	}
}