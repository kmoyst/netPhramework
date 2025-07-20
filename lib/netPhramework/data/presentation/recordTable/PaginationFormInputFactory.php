<?php

namespace netPhramework\data\presentation\recordTable;

use netPhramework\data\presentation\recordTable\collation\QueryKey;
use netPhramework\data\presentation\recordTable\{FormInputFactory as InputFactoryInterface};
use netPhramework\presentation\HiddenInput;
use netPhramework\presentation\Input;

class PaginationFormInputFactory implements InputFactoryInterface
{
	private FormInputConfigurator $sortInputConfigurator;
	private FormInputConfigurator $conditionInputConfigurator;

	public function __construct()
	{
		$this->sortInputConfigurator = new FormInputConfigurator(
			QueryKey::SORT_ARRAY->value, 'form/hidden-input-array');
		$this->conditionInputConfigurator = new FormInputConfigurator(
			QueryKey::CONDITION_SET->value, 'form/hidden-input-array');
	}

	public function makeLimitInput(): Input
	{
		return new HiddenInput(QueryKey::LIMIT->value);
	}

	public function makeOffsetInput(): Input
	{
		return new HiddenInput(QueryKey::OFFSET->value);
	}

	public function makeSortFieldInput(int $index): Input
	{
		$input = new HiddenInput(QueryKey::SORT_FIELD->value);
		$this->sortInputConfigurator
			->setIndex($index)
			->configureViewable($input);
		return $input;
	}

	public function makeSortDirectionInput(int $index): Input
	{
		$input = new HiddenInput(QueryKey::SORT_DIRECTION->value);
		$this->sortInputConfigurator
			->setIndex($index)
			->configureViewable($input);
		return $input;
	}

	public function makeConditionFieldInput(int $index): Input
	{
		$input = new HiddenInput(QueryKey::CONDITION_FIELD->value);
		$this->conditionInputConfigurator
			->setIndex($index)
			->configureViewable($input);
		return $input;
	}

	public function makeConditionOperatorInput(int $index): Input
	{
		$input = new HiddenInput(QueryKey::CONDITION_OPERATOR->value);
		$this->conditionInputConfigurator
			->setIndex($index)
			->configureViewable($input);
		return $input;
	}

	public function makeConditionValueInput(int $index): Input
	{
		$input = new HiddenInput(QueryKey::CONDITION_VALUE->value);
		$this->conditionInputConfigurator
			->setIndex($index)
			->configureViewable($input);
		return $input;
	}

	public function makeConditionGlueInput(int $index): Input
	{
		$input = new HiddenInput(QueryKey::CONDITION_GLUE->value);
		$this->conditionInputConfigurator
			->setIndex($index)
			->configureViewable($input);
		return $input;
	}
}