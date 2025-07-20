<?php

namespace netPhramework\data\presentation\recordTable;

use netPhramework\common\Utils;
use netPhramework\data\mapping\Glue;
use netPhramework\data\mapping\Operator;
use netPhramework\data\mapping\SortDirection;
use netPhramework\data\presentation\recordTable\collation\QueryKey;
use netPhramework\data\presentation\recordTable\{FormInputFactory as InputFactoryInterface};
use netPhramework\presentation\HiddenInput;
use netPhramework\presentation\Input;
use netPhramework\presentation\SelectInput;
use netPhramework\presentation\TextInput;

class QueryFormInputFactory implements InputFactoryInterface
{
	private array $columnNames;
	private FormInputConfigurator $sortInputConfigurator;
	private FormInputConfigurator $conditionInputConfigurator;

	public function __construct()
	{
		$this->sortInputConfigurator = new FormInputConfigurator(
			QueryKey::SORT_ARRAY->value, 'form/select-input-array'
		);
		$this->conditionInputConfigurator = new FormInputConfigurator(
			QueryKey::CONDITION_SET->value
		);
	}

	public function setColumnNames(array $columnNames): self
	{
		$this->columnNames = $columnNames;
		return $this;
	}

	public function makeLimitInput(): Input
	{
		return new SelectInput(QueryKey::LIMIT->value, $this->limitOptions());
	}

	public function makeOffsetInput(): Input
	{
		return new HiddenInput(QueryKey::OFFSET->value);
	}

	public function makeSortFieldInput(int $index): Input
	{
		$input = new SelectInput(
			QueryKey::SORT_FIELD->value,
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
			QueryKey::SORT_DIRECTION->value,
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
			QueryKey::CONDITION_FIELD->value,
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
			QueryKey::CONDITION_OPERATOR->value,
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
			QueryKey::CONDITION_VALUE->value);
		$this->conditionInputConfigurator
			->setIndex($index)
			->setTemplateName('form/text-input-array')
			->configureViewable($input);
		return $input;
	}

	public function makeConditionGlueInput(int $index): Input
	{
		$input = new SelectInput(
			QueryKey::CONDITION_GLUE->value, Glue::toArray());
		$this->conditionInputConfigurator
			->setIndex($index)
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