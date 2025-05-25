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
	private InputSet $conditionFieldInputs;
	private InputSet $conditionOperatorInputs;
	private InputSet $conditionValueInputs;
	private InputSet $conditionGlueInputs;

	public function __construct()
	{
		$this->sortFieldInputs = new InputSet();
		$this->sortDirectionInputs = new InputSet();
		$this->conditionFieldInputs = new InputSet();
		$this->conditionOperatorInputs = new InputSet();
		$this->conditionValueInputs = new InputSet();
		$this->conditionGlueInputs = new InputSet();
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

	public function addConditionFieldInput(Input $input):self
	{
		$this->conditionFieldInputs->addCustom($input);
		return $this;
	}

	public function addConditionOperatorInput(Input $input):self
	{
		$this->conditionFieldInputs->addCustom($input);
		return $this;
	}

	public function addConditionValueInput(Input $input):self
	{
		$this->conditionValueInputs->addCustom($input);
		return $this;
	}

	public function addCondtionGlueInput(Input $input):self
	{
		$this->conditionGlueInputs->addCustom($input);
		return $this;
	}

	public function getFilterForm(string $templateName):View
	{
		return new View($templateName)
			->add('conditionFieldInputs', $this->conditionFieldInputs)
			->add('conditionOperatorInputs', $this->conditionOperatorInputs)
			->add('conditionValueInputs', $this->conditionValueInputs)
			->add('conditionGlueInputs', $this->conditionGlueInputs)
			->add('sortFieldInputs', $this->sortFieldInputs)
			->add('sortDirectionInputs', $this->sortDirectionInputs)
			->add('limitInput', $this->limitInput)
			->add('offsetInput', $this->offsetInput)
		;
	}
}