<?php

namespace netPhramework\db\presentation\recordTable;

use netPhramework\rendering\View;

class SelectFilterDirector
{
	private FilterFormInputFactory $factory;
	private View $form;

	public function __construct(private readonly FilterFormDirector $director)
	{
		$this->factory = new SelectFilterFormInputFactory();
	}

	public function configure(array $columnHeaders):self
	{
		$this->factory->setColumnHeaders($columnHeaders);
		return $this;
	}

	public function buildSelectFilterForm(FilterFormContext $context):self
	{
		$builder = new FilterFormBuilder()
			->setFactory($this->factory)
			->setContext($context)
			;
		$this->director
			->setBuilder($builder)
			->constructForm()
		;
		$this->form = $builder
			->createFilterForm('filter-select');
		return $this;
	}

	public function getView():View
	{
		return $this->form;
	}
}