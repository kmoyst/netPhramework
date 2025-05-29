<?php

namespace netPhramework\presentation\FormInput;

use netPhramework\common\Variables;

class FileInput extends TextInput
{
	protected string $templateName = 'form/file-input';

	public function getVariables(): Variables
	{
		return parent::getVariables()
			->add('type', 'file')
			;
	}


}