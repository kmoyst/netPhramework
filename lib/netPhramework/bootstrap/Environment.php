<?php

namespace netPhramework\bootstrap;

use netPhramework\transferring\SmtpServerEnvironment;

interface Environment extends SmtpServerEnvironment
{
	protected(set) bool $inDevelopment {get;}
	protected(set) string $uri {get;}
	protected(set) ?array $postParameters {get;}
	protected(set) string $siteAddress {get;}
	protected(set) string $smtpServerName {get;}
	protected(set) string $smtpServerAddress {get;}
	public function getVariable(string $varName):?string;
}