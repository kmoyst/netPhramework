<?php

namespace netPhramework\core;

use netPhramework\transferring\SmtpServerEnvironment;

interface Environment extends SmtpServerEnvironment
{
	public bool $inDevelopment {get;}
	public string $uri {get;}
	public ?array $postParameters {get;}
	public string $siteAddress {get;}
	public string $smtpServerName {get;}
	public string $smtpServerAddress {get;}
	public function getVariable(string $varName):?string;
}