<?php

namespace netPhramework\http;

use netPhramework\core\Environment;

class HttpEnvironment extends Environment
{
	public string $siteAddress {
		get { return "$this->siteScheme://$this->siteHost"; }
		set {}
	}

	public string $siteScheme {
		get { return $this->getVariable('HTTPS') === 'on' ? 'https' : 'http'; }
		set {}
	}

	public string $siteHost {
		get { return $this->getVariable('HTTP_HOST'); }
		set {}
	}

	public function getVariable(string $varName):?string
	{
		return filter_input(INPUT_SERVER, $varName);
	}

}