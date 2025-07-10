<?php

namespace netPhramework\core;

interface ExchangeEnvironment extends SmtpServerContext
{
	public string $siteAddress{get;}
}