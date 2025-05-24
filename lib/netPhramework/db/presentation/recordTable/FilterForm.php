<?php

namespace netPhramework\db\presentation\recordTable;

use netPhramework\common\Variables;
use netPhramework\presentation\FormInput\Input;
use netPhramework\rendering\Viewable;

class FilterForm extends Viewable
{
	private string $formName;
	private string $buttonText;
	private string $templateName;
	private Input $limitInput;
	private Input $offsetInput;
	private Input $sortFieldInput;
	private Input $sortDirectionInput;

	public function setFormName(string $formName): FilterForm
	{
		$this->formName = $formName;
		return $this;
	}

	public function setButtonText(string $buttonText): FilterForm
	{
		$this->buttonText = $buttonText;
		return $this;
	}

	public function setTemplateName(string $templateName): FilterForm
	{
		$this->templateName = $templateName;
		return $this;
	}

	public function setLimitInput(Input $input): void
	{
		$this->limitInput = $input;
	}

	public function setOffsetInput(Input $input): void
	{
		$this->offsetInput = $input;
	}

	public function setSortFieldInput(Input $input): void
	{
		$this->sortFieldInput = $input;
	}

	public function setSortDirectionInput(Input $input): void
	{
		$this->sortDirectionInput = $input;
	}

	public function getTemplateName(): string
	{
		return $this->templateName;
	}

	public function getVariables(): iterable
	{
		return new Variables()
			->add('limitInput', $this->limitInput ?? '')
			->add('offsetInput', $this->offsetInput ?? '')
			->add('sortFieldInput', $this->sortFieldInput ?? '')
			->add('sortDirectionInput', $this->sortDirectionInput ?? '')
			->add('formName', $this->formName)
			->add('buttonText', $this->buttonText)
			;
	}
}