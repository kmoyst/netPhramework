<?php

namespace netPhramework\bootstrap;

use netPhramework\core\ExchangeEnvironment;
use netPhramework\core\RequestEnvironment;
use netPhramework\exceptions\ReadonlyException;

class Environment implements RequestEnvironment, ExchangeEnvironment
{
	public bool $inDevelopment {
		get{return $this->getVariable('ERROR_LEVEL') === 'DEVELOPMENT';}
		set{throw new ReadonlyException("Property is read-only");}
	}

	public string $uri {
		get { return filter_input(INPUT_SERVER, 'REQUEST_URI');}
	}

	public ?array $postParameters {
		get{return filter_input_array(INPUT_POST);}
	}

	public string $siteAddress {
		get
		{
			return "$this->scheme://$this->authority";
		}
	}

	public string $smtpServerName {
		get {
			return $this->getVariable('SMTP_SERVER_NAME');
		}
	}

	public string $smtpServerAddress {
		get {
			return $this->getVariable('SMTP_SERVER_ADDRESS');
		}
	}

	private string $scheme {
		get {return $this->getVariable('HTTPS') === 'on' ? 'https' : 'http';}
	}

	private string $authority {
		get {return $this->getVariable('HTTP_HOST');}
	}

	public function getVariable(string $varName):?string
	{
		return filter_input(INPUT_SERVER, $varName);
	}
}