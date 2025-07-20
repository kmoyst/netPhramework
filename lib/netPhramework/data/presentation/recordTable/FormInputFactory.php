<?php

namespace netPhramework\data\presentation\recordTable;

use netPhramework\presentation\Input;

interface FormInputFactory
{
	public function makeLimitInput():Input;
	public function makeOffsetInput():Input;
	public function makeSortFieldInput(int $index):Input;
	public function makeSortDirectionInput(int $index):Input;
	public function makeConditionFieldInput(int $index):Input;
	public function makeConditionOperatorInput(int $index):Input;
	public function makeConditionValueInput(int $index):Input;
	public function makeConditionGlueInput(int $index):Input;
}