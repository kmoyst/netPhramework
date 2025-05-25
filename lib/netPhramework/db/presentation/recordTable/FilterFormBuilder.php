<?php

namespace netPhramework\db\presentation\recordTable;

use netPhramework\presentation\FormInput\Input;
use netPhramework\presentation\FormInput\InputSet;
use netPhramework\rendering\View;

class FilterFormBuilder
{
	private Input $limitInput;
	private Input $offsetInput;
	private InputSet $sortFieldInputs;
	private InputSet $sortDirectionInputs;

	public function __construct()
	{
		$this->sortFieldInputs = new InputSet();
		$this->sortDirectionInputs = new InputSet();
	}

	public function addLimitInput(Input $input):self
	{
		$this->limitInput = $input;
		return $this;
	}

	public function addOffsetInput(Input $input):self
	{
		$this->offsetInput = $input;
		return $this;
	}

	public function addSortFieldInput(Input $input):self
	{
		$this->sortFieldInputs->addCustom($input);
		return $this;
	}

	public function addSortDirectionInput(Input $input):self
	{
		$this->sortDirectionInputs->addCustom($input);
		return $this;
	}

	public function getFilterForm(string $templateName):View
	{
		return new View($templateName)
			->add('sortFieldInputs', $this->sortFieldInputs)
			->add('sortDirectionInputs', $this->sortDirectionInputs)
			->add('limitInput', $this->limitInput)
			->add('offsetInput', $this->offsetInput)
		;
	}
}