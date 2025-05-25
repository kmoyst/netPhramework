<?php

namespace netPhramework\presentation\FormInput;

use netPhramework\common\Variables;
use netPhramework\rendering\Encodable;
use netPhramework\rendering\ConfigurableViewable;
use netPhramework\rendering\Viewable;

abstract class Input extends Viewable implements ConfigurableViewable
{
	protected string $templateName;
	protected Variables $variables;

	public function __construct(protected readonly string $name)
	{
		$this->variables = new Variables();
	}

	public function getName():string
	{
		return $this->name;
	}

	public function setTemplateName(string $templateName): self
	{
		$this->templateName = $templateName;
		return $this;
	}

	public function getTemplateName(): string
	{
		return $this->templateName;
	}

	public function add(
		string $key, Encodable|string|iterable|null $value):self
	{
		$this->variables->add($key, $value);
		return $this;
	}

	public function getVariables():iterable
	{
		return $this->variables;
	}

	abstract public function setValue(Encodable|string|null $value):self;
}