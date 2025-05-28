<?php

namespace netPhramework\presentation\FormInput;

interface InputSetBuilder
{
	public function hiddenInput(string $name):HiddenInput;
	public function textInput(string $name):TextInput;
	public function passwordInput(string $name):PasswordInput;
	public function selectInput(string $name, iterable $options):SelectInput;
}