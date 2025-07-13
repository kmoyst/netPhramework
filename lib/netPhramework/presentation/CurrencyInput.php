<?php

namespace netPhramework\presentation;

use netPhramework\common\Variables;
use netPhramework\exceptions\Exception;

class CurrencyInput extends TextInput
{
	/**
	 * @return Variables
	 * @throws Exception
	 */
	public function getVariables(): Variables
	{
		return parent::getVariables()
			->add('label', parent::getVariables()->get('label')." (in cents)");
	}

}