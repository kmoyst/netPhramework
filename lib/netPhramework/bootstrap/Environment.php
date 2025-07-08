<?php

namespace netPhramework\bootstrap;

use netPhramework\core\ExchangeEnvironment;
use netPhramework\core\RequestEnvironment;

readonly class Environment implements RequestEnvironment, ExchangeEnvironment
{
	public function getUri(): string
	{
		return filter_input(INPUT_SERVER, 'REQUEST_URI');
	}

	public function getPostParameters(): ?array
	{
		return filter_input_array(INPUT_POST);
	}

	public function getVariable(string $varName):?string
	{
		return filter_input(INPUT_SERVER, $varName);
	}

	public function inDevelopment():bool
	{
		return $this->getVariable('ERROR_LEVEL') === 'DEVELOPMENT';
	}

	public function getSiteAddress():string
	{
		$scheme = $this->getScheme();
		$authority = $this->getAuthority();
		return "$scheme://$authority";
	}

	public function getSmtpServerAddress(): string
	{
		return $this->getVariable('SMTP_SERVER_ADDRESS');
	}

	public function getSmtpServerName(): string
	{
		return $this->getVariable('SMTP_SERVER_NAME');
	}

	private function getScheme():string
	{
		return $this->getVariable('HTTPS') === 'on' ? 'https' : 'http';
	}

	private function getAuthority():string
	{
		return $this->getVariable('HTTP_HOST');
	}
}