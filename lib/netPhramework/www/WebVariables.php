<?php

namespace netPhramework\www;

use netPhramework\core\RuntimeVariables;

class WebVariables implements RuntimeVariables
{
	public function get(string $key): ?string
	{
		return filter_input(INPUT_SERVER, $key);
	}
}