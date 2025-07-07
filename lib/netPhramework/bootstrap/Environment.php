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

	public function get(string $varName):?string
	{
		return filter_input(INPUT_SERVER, $varName);
	}

	public function inDevelopment():bool
	{
		return $this->get('ERROR_LEVEL') === 'DEVELOPMENT';
	}

	public function getSiteAddress():string
	{
		$scheme = $this->getScheme();
		$authority = $this->getAuthority();
		return "$scheme://$authority";
	}

	public function getSmtpServerAddress(): string
	{
		return 'ssl://mail.moyst.ca:465';
	}

	public function getSmtpServerName(): string
	{
		return 'moyst.ca';
	}

	private function getScheme():string
	{
		return $this->get('HTTPS') === 'on' ? 'https' : 'http';
	}

	private function getAuthority():string
	{
		return $this->get('HTTP_HOST');
	}
}