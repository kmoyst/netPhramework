<?php

namespace netPhramework\db\presentation\recordTable;

use netPhramework\presentation\FormInput\HiddenInput;
use netPhramework\presentation\FormInput\Input;
use netPhramework\presentation\FormInput\SelectInput;

class FilterSelectFormStrategy implements FilterFormStrategy
{
	private array $columnHeaders;

	public function setColumnHeaders(array $columnHeaders): self
	{
		$this->columnHeaders = $columnHeaders;
		return $this;
	}

	public function getTemplateName(): string
	{
		return 'filter-select-form';
	}

	public function getFormName(): string
	{
		return 'filter-select-form';
	}

	public function getButtonText(): string
	{
		return 'Filter';
	}

	public function createLimitInput(string $key, ?int $value): Input
	{
		$options = $this->limitOptions();
		return (new SelectInput($key,$options))->setValue($value);
	}

	private function limitOptions():array
	{
		$a = ['' => '-- # per page --'];
		for($i=5;$i<=50;$i+=5) $a[$i] = $i;
		return $a;
	}

	public function createOffsetInput(string $key, int $value): Input
	{
		return new HiddenInput($key, $value);
	}

	public function createSortFieldInput(string $key, ?string $value): Input
	{
		$input = new SelectInput($key, $this->createSortOptions());
		if(!empty($sf = $value)) $input->setValue($sf);
		return $input;
	}

	private function createSortOptions():iterable
	{
		return array_merge(['' => "-- Sort Field --"], $this->columnHeaders);
	}

	public function createSortDirectionInput(string $key, int $value): Input
	{
		$options = ['ASCENDING', 'DESCENDING'];
		$input = new SelectInput($key, $options);
		return $input->setValue($value);
	}
}