<?php

namespace netPhramework\stubs;

class Environment extends \netPhramework\bootstrap\Environment
{
	protected(set) bool $inDevelopment {
		get { return true; }
		set {}
	}

	protected(set) string $uri {
		get { return '/'; }
		set {}
	}

	protected(set) ?array $postParameters {
		get { return null; }
		set {}
	}

	protected(set) string $siteAddress {
		get { return "$this->scheme://$this->authority"; }
		set {}
	}

	protected(set) string $smtpServerName {
		get { return "moyst.ca"; }
		set {}
	}

	protected(set) string $smtpServerAddress {
		get { return "ssl://mail.moyst.ca:465"; }
		set {}
	}

	protected string $scheme {
		get { return 'phpTest'; }
		set {}
	}

	protected string $authority {
		get { return 'testSite'; }
		set {}
	}

	public function getVariable(string $varName):?string
	{
		return filter_input(INPUT_SERVER, $varName);
	}
}