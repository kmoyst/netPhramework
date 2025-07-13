<?php

namespace netPhramework\bootstrap;

class WebEnvironment implements Environment
{
	protected(set) bool $inDevelopment {
		get { return $this->getVariable('ERROR_LEVEL') === 'DEVELOPMENT'; }
	}

	protected(set) string $uri {
		get { return filter_input(INPUT_SERVER, 'REQUEST_URI'); }
		set {}
	}

	protected(set) ?array $postParameters {
		get { return filter_input_array(INPUT_POST); }
		set {}
	}

	protected(set) string $siteAddress {
		get { return "$this->scheme://$this->authority"; }
		set {}
	}

	protected(set) string $smtpServerName {
		get { return $this->getVariable('SMTP_SERVER_NAME'); }
		set {}
	}

	protected(set) string $smtpServerAddress {
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