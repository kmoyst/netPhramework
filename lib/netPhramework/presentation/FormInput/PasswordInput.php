<?php

namespace netPhramework\presentation\FormInput;


use netPhramework\common\Variables;

class PasswordInput extends TextInput
{
	public function getVariables(): Variables
	{
		parent::getVariables();
		return $this->variables
			->add('type', 'password');
	}
}