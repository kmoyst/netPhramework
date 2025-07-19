<?php

namespace netPhramework\console;

use netPhramework\common\Variables;
use netPhramework\core\RuntimeContext;

class ConsoleRuntimeContext implements RuntimeContext
{
	private Variables $variables;

	public function initialize():self
	{
		$this->variables = new Variables();
		$dotenv = fopen('dotenv', 'r');
		while($line = fgets($dotenv))
		{
			preg_match('|^([A-Z_]+)=(.+)$|', $line, $m);
			$this->variables->add($m[1], $m[2]);
		}
		fclose($dotenv);
		return $this;
	}

	public function get(string $key): ?string
	{
		return $this->variables->getOrNull($key);
	}
}