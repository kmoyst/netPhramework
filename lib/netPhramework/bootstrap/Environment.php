<?php

namespace netPhramework\bootstrap;

use netPhramework\core\ExchangeEnvironment;
use netPhramework\core\RequestEnvironment;
use netPhramework\exceptions\ReadonlyException;

class Environment implements RequestEnvironment, ExchangeEnvironment
{
	public bool $inDevelopment {
		get {return $this->getVariable('ERROR_LEVEL') === 'DEVELOPMENT';}
		set {throw new ReadonlyException("Property is read-only");}
	}

	public string $uri {
		get {return filter_input(INPUT_SERVER, 'REQUEST_URI');}
		set {throw new ReadonlyException("Property is read-only");}
	}

	public ?array $postParameters {
		get {return filter_input_array(INPUT_POST);}
		set {throw new ReadonlyException("Property is read-only");}
	}

	public string $siteAddress {
		get {return "$this->scheme://$this->authority";}
		set {throw new ReadonlyException("Property is read-only");}
	}

	public string $smtpServerName {
		get {return $this->getVariable('SMTP_SERVER_NAME');}
		set {throw new ReadonlyException("Property is read-only");}
	}

	public string $smtpServerAddress {
		get {return $this->getVariable('SMTP_SERVER_ADDRESS');}
		set {throw new ReadonlyException("Property is read-only");}
	}

	private string $scheme {
		get {return $this->getVariable('HTTPS') === 'on' ? 'https' : 'http';}
		set {throw new ReadonlyException("Property is read-only");}
	}

	private string $authority {
		get {return $this->getVariable('HTTP_HOST');}
		set {throw new ReadonlyException("Property is read-only");}
	}

	public function getVariable(string $varName):?string
	{
		return filter_input(INPUT_SERVER, $varName);
	}
}