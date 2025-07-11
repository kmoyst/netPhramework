<?php

namespace netPhramework\exchange;

use netPhramework\networking\SmtpServerEnvironment;

interface ExchangeEnvironment extends SmtpServerEnvironment
{
	public string $siteAddress{get;}
	public bool $inDevelopment{get;}
}