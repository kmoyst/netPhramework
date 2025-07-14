<?php

namespace netPhramework\core;

interface Environment
{
	public bool $inDevelopment {get;}
	public string $uri {get;}
	public ?array $postParameters {get;}
	public string $siteAddress {get;}
	public string $smtpServerName {get;}
	public string $smtpServerAddress {get;}
	public string $siteHost {get;}
	public string $siteScheme {get;}
	public function getVariable(string $varName):?string;
}