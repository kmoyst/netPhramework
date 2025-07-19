<?php

namespace netPhramework\console;

use netPhramework\common\Variables;
use netPhramework\core\RuntimeContext;

class ConsoleContext implements RuntimeContext
{
	private Variables $variables;

	public function initializeVariables():void
	{
		if(isset($this->variables)) return;
		$this->variables = new Variables();
		$dotenv = fopen('dotenv', 'r');
		while($line = fgets($dotenv))
		{
			preg_match('|^([A-Z_]+)=(.+)$|', $line, $m);
			$this->variables->add($m[1], $m[2]);
		}
		fclose($dotenv);
	}

	public function get(string $key): ?string
	{
		$this->initializeVariables();
		return $this->variables->getOrNull($key);
	}
}