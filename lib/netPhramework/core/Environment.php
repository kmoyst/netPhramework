<?php

namespace netPhramework\core;

abstract class Environment
{
	private(set) bool $inDevelopment {
		get { return $this->getVariable('ERROR_LEVEL') === 'DEVELOPMENT'; }
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

	abstract public string $siteAddress {get;}

	abstract public string $siteScheme {get;}

	abstract public string $siteHost {get;}

	abstract public function getVariable(string $varName):?string;
}