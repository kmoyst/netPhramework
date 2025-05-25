<?php

namespace netPhramework\db\presentation\recordTable\FilterSelectForm;

use netPhramework\db\mapping\SortDirection;
use netPhramework\db\presentation\recordTable\FilterForm\FilterFormInputFactory;
use netPhramework\db\presentation\recordTable\FilterKey;
use netPhramework\presentation\FormInput\HiddenInput;
use netPhramework\presentation\FormInput\Input;
use netPhramework\presentation\FormInput\SelectInput;

class FilterSelectFormInputFactory implements FilterFormInputFactory
{
	private array $columnHeaders;
	private string $templateParentKey = 'parentName';
	private string $templateIndexKey = 'index';

	public function setColumnHeaders(array $columnHeaders): self
	{
		$this->columnHeaders = $columnHeaders;
		return $this;
	}

	public function makeLimitInput(FilterKey $key): Input
	{
		return new SelectInput($key->value, $this->limitOptions());
	}

	private function limitOptions():iterable
	{
		$a = [];
		$a[''] = '---# PER PAGE---';
		for($i=5;$i<=50;$i+=5)
			$a[$i] = "$i PER PAGE";
		return $a;
	}

	public function makeOffsetInput(FilterKey $key): Input
	{
		return new HiddenInput($key->value);
	}

	public function makeSortFieldInput(
		FilterKey $key, FilterKey $parentKey, int $index): Input
	{
		return new SelectInput($key->value, $this->sortFieldOptions())
			->setTemplateName('form/select-input-array')
			->addVariable($this->templateParentKey, $parentKey->value)
			->addVariable($this->templateIndexKey, $index)
			;
	}

	public function makeSortDirectionInput(
		FilterKey $key, FilterKey $parentKey, int $index): Input
	{
		return new SelectInput($key->value, SortDirection::toArray())
			->setTemplateName('form/select-input-array')
			->addVariable($this->templateParentKey, $parentKey->value)
			->addVariable($this->templateIndexKey, $index)
			;
	}

	private function sortFieldOptions():array
	{
		$blank = ['' => '---SORT FIELD---'];
		return array_merge_recursive($blank, $this->columnHeaders);
	}

}