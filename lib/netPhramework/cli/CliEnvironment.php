<?php

namespace netPhramework\cli;

use netPhramework\common\Variables;
use netPhramework\core\Environment;

class CliEnvironment extends Environment
{
	public function __construct
	(
	public readonly Variables $variables = new Variables()
	)
	{}

	public function add(string $key, string $value):self
	{
		$this->variables->add($key, $value);
		return $this;
	}

	public string $siteAddress {
		get { return "$this->siteScheme://$this->siteHost"; }
		set {}
	}

	public string $siteScheme {
		get { return 'CLI'; }
		set {}
	}

	public string $siteHost {
		get { return $this->getVariable('HOST'); }
		set {}
	}

	public function getVariable(string $varName):?string
	{
		return $this->variables->getOrNull($varName);
	}
}