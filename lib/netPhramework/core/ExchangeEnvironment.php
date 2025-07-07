<?php

namespace netPhramework\core;

interface ExchangeEnvironment
{
	public function getSiteAddress():string;
	public function getSmtpServerAddress():string;
	public function getSmtpServerName():string;
}