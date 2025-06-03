<?php

namespace netPhramework\db\presentation\recordTable;

use netPhramework\db\presentation\recordTable\{FormInputFactory as InputFactoryInterface};
use netPhramework\db\presentation\recordTable\query\Key;
use netPhramework\presentation\HiddenInput;
use netPhramework\presentation\Input;

class PaginationFormInputFactory implements InputFactoryInterface
{
	private FormInputConfigurator $sortInputConfigurator;
	private FormInputConfigurator $conditionInputConfigurator;

	public function __construct()
	{
		$this->sortInputConfigurator = new FormInputConfigurator(
			Key::SORT_ARRAY->value, 'form/hidden-input-array');
		$this->conditionInputConfigurator = new FormInputConfigurator(
			Key::CONDITION_SET->value, 'form/hidden-input-array');
	}

	public function makeLimitInput(): Input
	{
		return new HiddenInput(Key::LIMIT->value);
	}

	public function makeOffsetInput(): Input
	{
		return new HiddenInput(Key::OFFSET->value);
	}

	public function makeSortFieldInput(int $index): Input
	{
		$input = new HiddenInput(Key::SORT_FIELD->value);
		$this->sortInputConfigurator
			->setIndex($index)
			->configureViewable($input);
		return $input;
	}

	public function makeSortDirectionInput(int $index): Input
	{
		$input = new HiddenInput(Key::SORT_DIRECTION->value);
		$this->sortInputConfigurator
			->setIndex($index)
			->configureViewable($input);
		return $input;
	}

	public function makeConditionFieldInput(int $index): Input
	{
		$input = new HiddenInput(Key::CONDITION_FIELD->value);
		$this->conditionInputConfigurator
			->setIndex($index)
			->configureViewable($input);
		return $input;
	}

	public function makeConditionOperatorInput(int $index): Input
	{
		$input = new HiddenInput(Key::CONDITION_OPERATOR->value);
		$this->conditionInputConfigurator
			->setIndex($index)
			->configureViewable($input);
		return $input;
	}

	public function makeConditionValueInput(int $index): Input
	{
		$input = new HiddenInput(Key::CONDITION_VALUE->value);
		$this->conditionInputConfigurator
			->setIndex($index)
			->configureViewable($input);
		return $input;
	}

	public function makeConditionGlueInput(int $index): Input
	{
		$input = new HiddenInput(Key::CONDITION_GLUE->value);
		$this->conditionInputConfigurator
			->setIndex($index)
			->configureViewable($input);
		return $input;
	}
}