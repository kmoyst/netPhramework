<?php

namespace netPhramework\presentation\FormInput;


use netPhramework\common\Variables;

class PasswordInput extends TextInput
{
	public function getVariables(): Variables
	{
		return parent::getVariables()->add('type', 'password');
	}
}