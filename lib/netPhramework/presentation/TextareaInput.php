<?php

namespace netPhramework\presentation;

use netPhramework\common\Variables;

class TextareaInput extends TextInput
{
	protected string $templateName = 'form/textarea-input';

	public function getVariables(): Variables
	{
		return parent::getVariables()
			->add('rows', 8)
			->add('cols', 40)
			;
	}
}