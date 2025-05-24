<?php

namespace netPhramework\presentation\FormInput;

use Iterator;
use netPhramework\common\Utils;
use netPhramework\common\Variables;
use netPhramework\rendering\View;

class InputSet implements Iterator, InputSetBuilder
{
	private array $inputs = [];

	public function __construct() {}

	public function generateView(string $templateName): View
	{
		$view = new View($templateName);
		$this->populateVariables($view->getVariables());
		return $view;
	}

	public function populateVariables(Variables $vars): void
	{
		foreach($this->inputs as $key => $input)
			$vars->add(Utils::kebabToCamel($key), $input);
	}

	public function hiddenInput(string $name):HiddenInput
	{
		$input = new HiddenInput($name);
		$this->inputs[$name] = $input;
		return $input;
	}

	public function textInput(string $name):TextInput
	{
		$input = new TextInput($name);
		$this->inputs[$name] = $input;
		return $input;
	}

	public function passwordInput(string $name):PasswordInput
	{
		$input = new PasswordInput($name);
		$this->inputs[$name] = $input;
		return $input;
	}

	public function selectInput(string $name):SelectInput
	{

	}

	public function selectInputWithLabel(string $name):SelectInputWithLabel
	{

	}

	public function addCustom(Input $input):InputSet
	{
		$this->inputs[$input->getName()] = $input;
		return $this;
	}

	public function current(): Input
	{
		return current($this->inputs);
	}

	public function next(): void
	{
		next($this->inputs);
	}

	public function key(): string
	{
		return key($this->inputs);
	}

	public function valid(): bool
	{
		return key($this->inputs) !== null;
	}

	public function rewind(): void
	{
		reset($this->inputs);
	}
}