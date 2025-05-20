<?php

namespace netPhramework\db\presentation\recordTable;

use netPhramework\presentation\FormInput\Input;

interface FilterFormStrategy
{
	public function getTemplateName():string;
	public function getFormName():string;
	public function getButtonText():string;
	public function createLimitInput(string $key, ?int $value):Input;
	public function createOffsetInput(string $key, int $value):Input;
	public function createSortFieldInput(string $key, string $value):Input;
	public function createSortDirectionInput(string $key, int $value):Input;
}