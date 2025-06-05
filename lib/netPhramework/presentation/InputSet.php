<?php

namespace netPhramework\presentation;

use Iterator;
use netPhramework\common\Utils;
use netPhramework\common\Variables;
use netPhramework\rendering\View;

class InputSet implements Iterator, InputSetBuilder
{
	private array $inputs = [];
	private bool $hasFileInput = false;

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

	public function selectInput(string $name, iterable $options):SelectInput
	{
		$input = new SelectInput($name, $options);
		$this->inputs[$name] = $input;
		return $input;
	}

	public function checkboxInput(string $name): CheckboxInput
	{
		$input = new CheckboxInput($name);
		$this->inputs[$name] = $input;
		return $input;
	}

	public function fileInput(string $name): FileInput
	{
		$input = new FileInput($name);
		$this->inputs[$name] = $input;
		$this->hasFileInput = true;
		return $input;
	}

	public function textareaInput(string $name): TextareaInput
	{
		$input = new TextareaInput($name);
		$this->inputs[$name] = $input;
		return $input;
	}

	public function currencyInput(string $name): CurrencyInput
	{
		$input = new CurrencyInput($name);
		$this->inputs[$name] = $input;
		return $input;
	}

	public function hasFileInput(): bool
	{
		return $this->hasFileInput;
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