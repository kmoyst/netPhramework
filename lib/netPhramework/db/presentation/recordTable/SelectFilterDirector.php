<?php

namespace netPhramework\db\presentation\recordTable;

use netPhramework\rendering\View;

class SelectFilterDirector
{
	private FilterFormDirector $director;
	private FilterFormInputFactory $factory;
	private View $form;

	public function __construct()
	{
		$this->factory = new SelectFilterFormInputFactory();
	}

	public function setDirector(FilterFormDirector $director): self
	{
		$this->director = $director;
		return $this;
	}

	public function configure(
		FilterFormContext $context, array $columnHeaders):self
	{
		$this->factory->setColumnHeaders($columnHeaders);
		$this->director
			->setFactory($this->factory)
			->setContext($context)
		;
		return $this;
	}

	public function buildForm():self
	{
		$builder = new FilterFormBuilder();
		$this->director
			->setBuilder($builder)
			->buildFilterForm();
		$this->form = $builder->getFilterForm('filter-select');
		return $this;
	}

	public function getView():View
	{
		return $this->form;
	}
}