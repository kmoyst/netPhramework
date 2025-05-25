<?php

namespace netPhramework\db\presentation\recordTable\FilterSelectForm;

use netPhramework\db\mapping\SortDirection;
use netPhramework\db\presentation\recordTable\FilterForm\FilterFormInputConfigurator;
use netPhramework\db\presentation\recordTable\FilterForm\FilterFormInputFactory;
use netPhramework\db\presentation\recordTable\FilterKey;
use netPhramework\presentation\FormInput\HiddenInput;
use netPhramework\presentation\FormInput\Input;
use netPhramework\presentation\FormInput\SelectInput;

class FilterSelectFormInputFactory implements FilterFormInputFactory
{
	private array $columnHeaders;
	private FilterFormInputConfigurator $sortInputConfigurator;

	public function __construct()
	{
		$this->sortInputConfigurator = new FilterFormInputConfigurator(
			FilterKey::SORT_ARRAY->value, 'form/select-input-array'
		);
	}

	public function setColumnHeaders(array $columnHeaders): self
	{
		$this->columnHeaders = $columnHeaders;
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

	private function sortFieldOptions():array
	{
		$blank = ['' => '---SORT FIELD---'];
		return array_merge_recursive($blank, $this->columnHeaders);
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