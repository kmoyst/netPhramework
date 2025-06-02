<?php

namespace netPhramework\db\presentation\recordTable\paginator\form;

use netPhramework\db\presentation\recordTable\
{
	form\InputConfigurator,
	form\InputFactory as InputFactoryInterface
};
use netPhramework\db\presentation\recordTable\query\Key;
use netPhramework\presentation\HiddenInput;
use netPhramework\presentation\Input;

class InputFactory implements InputFactoryInterface
{
	private InputConfigurator $sortInputConfigurator;
	private InputConfigurator $conditionInputConfigurator;

	public function __construct()
	{
		$this->sortInputConfigurator = new InputConfigurator(
			Key::SORT_ARRAY->value, 'form/hidden-input-array');
		$this->conditionInputConfigurator = new InputConfigurator(
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