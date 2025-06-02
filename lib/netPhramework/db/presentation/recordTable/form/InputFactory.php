<?php

namespace netPhramework\db\presentation\recordTable\form;

use netPhramework\presentation\Input;

interface InputFactory
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