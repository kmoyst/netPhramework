<?php

namespace netPhramework\bootstrap;

class Environment
{
	public function get(string $varName):?string
	{
		return filter_input(INPUT_SERVER, $varName);
	}

	public function inDevelopment():bool
	{
		return $this->get('ERROR_LEVEL') === 'DEVELOPMENT';
	}
}