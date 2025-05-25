<?php

namespace netPhramework\db\presentation\recordTable\FilterForm;

use netPhramework\presentation\FormInput\Input;

interface FilterFormInputFactory
{
	public function makeLimitInput():Input;
	public function makeOffsetInput():Input;
	public function makeSortFieldInput(int $index):Input;
	public function makeSortDirectionInput(int $index):Input;
}