<?php

namespace netPhramework\core;

class WebEnvironment implements Environment
{
	private(set) bool $inDevelopment {
		get { return $this->getVariable('ERROR_LEVEL') === 'DEVELOPMENT'; }
		set {}
	}

	private(set) string $uri {
		get { return filter_input(INPUT_SERVER, 'REQUEST_URI'); }
		set {}
	}

	private(set) ?array $postParameters {
		get { return filter_input_array(INPUT_POST); }
		set {}
	}

	private(set) string $siteAddress {
		get { return "$this->siteScheme://$this->siteHost"; }
		set {}
	}

	private(set) string $smtpServerName {
		get { return $this->getVariable('SMTP_SERVER_NAME'); }
		set {}
	}

	private(set) string $smtpServerAddress {
		get { return $this->getVariable('SMTP_SERVER_ADDRESS'); }
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