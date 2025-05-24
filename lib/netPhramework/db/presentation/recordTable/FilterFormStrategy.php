<?php

namespace netPhramework\db\presentation\recordTable;

use netPhramework\presentation\FormInput\Input;

interface FilterFormStrategy
{
	public function getTemplateName():string;
	public function getFormName():string;
	public function getButtonText():string;
	public function createLimitInput(string $name):Input;
	public function createOffsetInput(string $name):Input;
	public function createSortFieldInput(string $name):Input;
	public function createSortDirectionInput(string $name):Input;
}