<?php

namespace netPhramework\bootstrap;

class Environment
{
	/**
	 * @param string $varName
	 * @return string|null
	 */
	public function get(string $varName):?string
	{
		return filter_input(INPUT_SERVER, $varName);
	}

	public function inDevelopment():bool
	{
		return filter_input(INPUT_SERVER, 'ERROR_LEVEL') === 'DEVELOPMENT';
	}
}