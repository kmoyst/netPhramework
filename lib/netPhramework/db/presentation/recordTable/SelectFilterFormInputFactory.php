<?php

namespace netPhramework\db\presentation\recordTable;

use netPhramework\common\Utils;
use netPhramework\db\mapping\Glue;
use netPhramework\db\mapping\Operator;
use netPhramework\db\mapping\SortDirection;
use netPhramework\presentation\FormInput\HiddenInput;
use netPhramework\presentation\FormInput\Input;
use netPhramework\presentation\FormInput\SelectInput;
use netPhramework\presentation\FormInput\TextInput;

class SelectFilterFormInputFactory implements FilterFormInputFactory
{
	private array $columnNames;
	private FilterFormInputConfigurator $sortInputConfigurator;
	private FilterFormInputConfigurator $conditionInputConfigurator;

	public function __construct()
	{
		$this->sortInputConfigurator = new FilterFormInputConfigurator(
			FilterKey::SORT_ARRAY->value, 'form/select-input-array'
		);
		$this->conditionInputConfigurator = new FilterFormInputConfigurator(
			FilterKey::CONDITION_SET->value
		);
	}

	public function setColumnNames(array $columnNames): self
	{
		$this->columnNames = $columnNames;
		return $this;
	}

	public function makeLimitInput(): Input
	{
		return new SelectInput(FilterKey::LIMIT->value, $this->limitOptions());
	}

	public function makeOffsetInput(): Input
	{
		return new HiddenInput(FilterKey::OFFSET->value);
	}

	public function makeSortFieldInput(int $index): Input
	{
		$input = new SelectInput(
			FilterKey::SORT_FIELD->value,
			$this->sortFieldOptions());
		$this->sortInputConfigurator
			->setIndex($index)
			->configureViewable($input)
		;
		return $input;
	}

	public function makeSortDirectionInput(int $index): Input
	{
		$input = new SelectInput(
			FilterKey::SORT_DIRECTION->value,
			SortDirection::toArray());
		$this->sortInputConfigurator
			->setIndex($index)
			->configureViewable($input)
		;
		return $input;
	}

	public function makeConditionFieldInput(int $index): Input
	{
		$input = new SelectInput(
			FilterKey::CONDITION_FIELD->value,
			$this->conditionFieldOptions());
		$this->conditionInputConfigurator
			->setIndex($index)
			->setTemplateName('form/select-input-array')
			->configureViewable($input);
		return $input;
	}

	public function makeConditionOperatorInput(int $index): Input
	{
		$input = new SelectInput(
			FilterKey::CONDITION_OPERATOR->value,
			Operator::toArray());
		$this->conditionInputConfigurator
			->setIndex($index)
			->setTemplateName('form/select-input-array')
			->configureViewable($input);
		return $input;
	}

	public function makeConditionValueInput(int $index): Input
	{
		$input = new TextInput(
			FilterKey::CONDITION_VALUE->value);
		$this->conditionInputConfigurator
			->setIndex($index)
			->setTemplateName('form/text-input-array')
			->configureViewable($input);
		return $input;
	}

	public function makeConditionGlueInput(int $index): Input
	{
		$input = new SelectInput(
			FilterKey::CONDITION_GLUE->value, Glue::toArray());
		$this->conditionInputConfigurator
			->setTemplateName($index)
			->setTemplateName('form/select-input-array')
			->configureViewable($input);
		return $input;
	}

	private function sortFieldOptions():array
	{
		$blank = ['' => '---SORT FIELD---'];
		return array_merge($blank, $this->fieldOptions());
	}

	private function conditionFieldOptions():array
	{
		$blank = ['' => '---CONDITION FIELD---'];
		return array_merge($blank, $this->fieldOptions());
	}

	private function fieldOptions():array
	{
		$options = [];
		foreach ($this->columnNames as $name)
		{
			$options[$name] = Utils::kebabToSpace($name);
		}
		return $options;
	}

	private function limitOptions():iterable
	{
		$a = [];
		$a[''] = '---# PER PAGE---';
		for($i=5;$i<=50;$i+=5)
			$a[$i] = "$i PER PAGE";
		return $a;
	}
}