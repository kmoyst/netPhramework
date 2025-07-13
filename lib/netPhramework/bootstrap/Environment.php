<?php

namespace netPhramework\bootstrap;

use netPhramework\transferring\SmtpServerEnvironment;

class Environment implements SmtpServerEnvironment
{
	protected(set) bool $inDevelopment {
		get { return $this->getVariable('ERROR_LEVEL') === 'DEVELOPMENT'; }
		set {}
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

	protected string $scheme {
		get { return $this->getVariable('HTTPS') === 'on' ? 'https' : 'http'; }
		set {}
	}

	protected string $authority {
		get { return $this->getVariable('HTTP_HOST'); }
		set {}
	}

	public function getVariable(string $varName):?string
	{
		return filter_input(INPUT_SERVER, $varName);
	}
}