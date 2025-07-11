<?php

namespace netPhramework\bootstrap;

use netPhramework\exchange\RequestEnvironment;

class Environment implements RequestEnvironment
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
		get { return "$this->scheme://$this->authority"; }
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

	private string $scheme {
		get { return $this->getVariable('HTTPS') === 'on' ? 'https' : 'http'; }
		set {}
	}

	private string $authority {
		get { return $this->getVariable('HTTP_HOST'); }
		set {}
	}

	public function getVariable(string $varName):?string
	{
		return filter_input(INPUT_SERVER, $varName);
	}
}