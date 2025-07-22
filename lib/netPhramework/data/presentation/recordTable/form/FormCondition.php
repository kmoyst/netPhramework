<?php

namespace netPhramework\data\presentation\recordTable\form;

use netPhramework\presentation\Input;
use netPhramework\rendering\EncodableViewable;

class FormCondition extends EncodableViewable
{
	private Input $fieldInput;
	private Input $operatorInput;
	private Input $valueInput;
	private ?Input $glueInput;

	public function __construct(private readonly string $templateName) {}

	public function setFieldInput(Input $fieldInput): self
	{
		$this->fieldInput = $fieldInput;
		return $this;
	}

	public function setOperatorInput(Input $operatorInput): self
	{
		$this->operatorInput = $operatorInput;
		return $this;
	}

	public function setValueInput(Input $valueInput): self
	{
		$this->valueInput = $valueInput;
		return $this;
	}

	public function setGlueInput(?Input $glueInput): self
	{
		$this->glueInput = $glueInput;
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
		$a['operatorInput'] = $this->operatorInput;
		$a['valueInput'] = $this->valueInput;
		$a['glueInput'] = $this->glueInput ?? '';
		return $a;
	}
}