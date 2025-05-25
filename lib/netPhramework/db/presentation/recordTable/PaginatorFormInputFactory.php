<?php

namespace netPhramework\db\presentation\recordTable;

use netPhramework\presentation\FormInput\HiddenInput;
use netPhramework\presentation\FormInput\Input;

class PaginatorFormInputFactory implements FilterFormInputFactory
{
	private FilterFormInputConfigurator $sortInputConfigurator;

	public function __construct()
	{
		$this->sortInputConfigurator = new FilterFormInputConfigurator(
			FilterKey::SORT_ARRAY->value, 'form/hidden-input-array');
	}

	public function makeLimitInput(): Input
	{
		return new HiddenInput(FilterKey::LIMIT->value);
	}

	public function makeOffsetInput(): Input
	{
		return new HiddenInput(FilterKey::OFFSET->value);
	}

	public function makeSortFieldInput(int $index): Input
	{
		$input = new HiddenInput(FilterKey::SORT_FIELD->value);
		$this->sortInputConfigurator
			->setIndex($index)
			->configureViewable($input);
		return $input;
	}

	public function makeSortDirectionInput(int $index): Input
	{
		$input = new HiddenInput(FilterKey::SORT_DIRECTION->value);
		$this->sortInputConfigurator
			->setIndex($index)
			->configureViewable($input);
		return $input;
	}
}