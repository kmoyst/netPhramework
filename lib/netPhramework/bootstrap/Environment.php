<?php

namespace netPhramework\bootstrap;

use netPhramework\common\Variables;
use netPhramework\core\Exception;

class Environment
{
	private Variables $variables;

	public function __construct()
	{
		$this->variables = new Variables()->merge($_SERVER);
	}

	public function getVariables():Variables
	{
		return $this->variables;
	}

	/**
	 * @param string $varName
	 * @return string
	 * @throws Exception
	 */
	public function get(string $varName):string
	{
		return $this->variables->get($varName);
	}

	public function inDevelopment():bool
	{
		return $this->variables->getOrNull('ERROR_LEVEL') === 'DEVELOPMENT';
	}
}