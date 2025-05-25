<?php

namespace netPhramework\db\presentation\recordTable\PaginatorForm;

use netPhramework\db\presentation\recordTable\FilterForm\FilterFormInputFactory;
use netPhramework\db\presentation\recordTable\FilterKey;
use netPhramework\presentation\FormInput\HiddenInput;
use netPhramework\presentation\FormInput\Input;

class PaginatorFormInputFactory implements FilterFormInputFactory
{
	private string $templateParentKey = 'parentName';
	private string $templateIndexKey = 'index';

	public function makeLimitInput(FilterKey $key): Input
	{
		return new HiddenInput($key->value);
	}

	public function makeOffsetInput(FilterKey $key): Input
	{
		return new HiddenInput($key->value);
	}

	public function makeSortFieldInput(
		FilterKey $key, FilterKey $parentKey, int $index): Input
	{
		return new HiddenInput($key->value)
			->setTemplateName('form/hidden-input-array')
			->addVariable($this->templateParentKey, $parentKey->value)
			->addVariable($this->templateIndexKey, $index)
			;
	}

	public function makeSortDirectionInput(
		FilterKey $key, FilterKey $parentKey, int $index): Input
	{
		return new HiddenInput($key->value)
			->setTemplateName('form/hidden-input-array')
			->addVariable($this->templateParentKey, $parentKey->value)
			->addVariable($this->templateIndexKey, $index)
			;
	}
}