<?php

namespace netPhramework\exchange;

use netPhramework\transferring\SmtpServerEnvironment;

interface ExchangeEnvironment extends SmtpServerEnvironment
{
	public string $siteAddress{get;}
	public bool $inDevelopment{get;}
}