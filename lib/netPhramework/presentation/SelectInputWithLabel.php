<?php

namespace netPhramework\presentation;

use netPhramework\common\Utils;
use netPhramework\common\Variables;

class SelectInputWithLabel extends SelectInput
{
	protected string $templateName = 'select-input-with-label';

	public function getVariables(): Variables
	{
		return parent::getVariables()
			->add('label', $this->label ?? Utils::kebabToSpace($this->name));
	}
}