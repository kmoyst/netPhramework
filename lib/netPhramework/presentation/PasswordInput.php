<?php

namespace netPhramework\presentation;


use netPhramework\common\Variables;

class PasswordInput extends TextInput
{
	public function getVariables(): Variables
	{
		return parent::getVariables()
			->add('type', 'password')
			->add('value', '')
			;
	}
}