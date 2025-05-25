<?php

namespace netPhramework\db\presentation\recordTable;

use netPhramework\presentation\FormInput\Input;
use netPhramework\rendering\Viewable;

class FilterFormSortVector extends Viewable
{
	private Input $fieldInput;
	private Input $directionInput;

	public function __construct(private readonly string $templateName) {}

	public function setFieldInput(Input $fieldInput): self
	{
		$this->fieldInput = $fieldInput;
		return $this;
	}

	public function setDirectionInput(Input $directionInput): self
	{
		$this->directionInput = $directionInput;
		return $this;
	}

	public function getTemplateName(): string
	{
		return $this->templateName;
	}

	public function getVariables(): iterable
	{
		$a = [];
		$a['fieldInput'] = $this->fieldInput;
		$a['directionInput'] = $this->directionInput;
		return $a;
	}
}