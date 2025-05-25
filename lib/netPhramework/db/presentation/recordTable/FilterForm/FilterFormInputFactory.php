<?php

namespace netPhramework\db\presentation\recordTable\FilterForm;

use netPhramework\db\presentation\recordTable\FilterKey;
use netPhramework\presentation\FormInput\Input;

interface FilterFormInputFactory
{
	public function makeLimitInput(FilterKey $key):Input;
	public function makeOffsetInput(FilterKey $key):Input;
	public function makeSortFieldInput(
		FilterKey $key, FilterKey $parentKey, int $index):Input;
	public function makeSortDirectionInput(
		FilterKey $key, FilterKey $parentKey, int $index):Input;
}