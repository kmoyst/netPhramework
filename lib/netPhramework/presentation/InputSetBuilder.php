<?php

namespace netPhramework\presentation;

interface InputSetBuilder
{
	public function hiddenInput(string $name):HiddenInput;
	public function textInput(string $name):TextInput;
	public function passwordInput(string $name):PasswordInput;
	public function selectInput(string $name, iterable $options):SelectInput;
	public function selectInputWithLabel(
		string $name, iterable $options):SelectInputWithLabel;
	public function checkboxInput(string $name):CheckboxInput;
	public function fileInput(string $name):FileInput;
	public function textareaInput(string $name): TextareaInput;
	public function currencyInput(string $name): CurrencyInput;
}