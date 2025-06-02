<?php

namespace netPhramework\db\presentation\recordTable\paginator;

use netPhramework\db\presentation\recordTable\configuration\FilterKey;
use netPhramework\db\presentation\recordTable\filterForm\FilterFormInputConfigurator;
use netPhramework\db\presentation\recordTable\filterForm\FilterFormInputFactory;
use netPhramework\presentation\HiddenInput;
use netPhramework\presentation\Input;

class PaginatorFormInputFactory implements FilterFormInputFactory
{
	private FilterFormInputConfigurator $sortInputConfigurator;
	private FilterFormInputConfigurator $conditionInputConfigurator;

	public function __construct()
	{
		$this->sortInputConfigurator = new FilterFormInputConfigurator(
			FilterKey::SORT_ARRAY->value, 'form/hidden-input-array');
		$this->conditionInputConfigurator = new FilterFormInputConfigurator(
			FilterKey::CONDITION_SET->value, 'form/hidden-input-array');
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

	public function makeConditionFieldInput(int $index): Input
	{
		$input = new HiddenInput(FilterKey::CONDITION_FIELD->value);
		$this->conditionInputConfigurator
			->setIndex($index)
			->configureViewable($input);
		return $input;
	}

	public function makeConditionOperatorInput(int $index): Input
	{
		$input = new HiddenInput(FilterKey::CONDITION_OPERATOR->value);
		$this->conditionInputConfigurator
			->setIndex($index)
			->configureViewable($input);
		return $input;
	}

	public function makeConditionValueInput(int $index): Input
	{
		$input = new HiddenInput(FilterKey::CONDITION_VALUE->value);
		$this->conditionInputConfigurator
			->setIndex($index)
			->configureViewable($input);
		return $input;
	}

	public function makeConditionGlueInput(int $index): Input
	{
		$input = new HiddenInput(FilterKey::CONDITION_GLUE->value);
		$this->conditionInputConfigurator
			->setIndex($index)
			->configureViewable($input);
		return $input;
	}
}