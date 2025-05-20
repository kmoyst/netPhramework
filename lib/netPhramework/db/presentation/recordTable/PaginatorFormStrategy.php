<?php

namespace netPhramework\db\presentation\recordTable;

use netPhramework\presentation\FormInput\HiddenInput;
use netPhramework\presentation\FormInput\Input;

class PaginatorFormStrategy implements FilterFormStrategy
{
	private string $templateName = 'paginator-form';

	public function __construct(
		private readonly string $formName,
		private readonly string $buttonText) {}

	public function getFormName(): string
	{
		return $this->formName;
	}

	public function getButtonText(): string
	{
		return $this->buttonText;
	}

	public function getTemplateName(): string
	{
		return $this->templateName;
	}

	public function createLimitInput(string $key, ?int $value): Input
	{
		return new HiddenInput($key, $value);
	}

	public function createOffsetInput(string $key, int $value): Input
	{
		return new HiddenInput($key, $value);
	}

	public function createSortFieldInput(string $key, ?string $value): Input
	{
		return new HiddenInput($key, $value);
	}

	public function createSortDirectionInput(string $key, int $value): Input
	{
		return new HiddenInput($key, $value);
	}
}