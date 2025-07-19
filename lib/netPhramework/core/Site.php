<?php

namespace netPhramework\core;

use netPhramework\exchange\Responder;
use netPhramework\exchange\Services;

abstract class Site
{
	public bool $inDevelopment {get{
		return $this->runtime->mode === RuntimeMode::DEVELOPMENT;
	}}

	public string $address {get{
		return $this->runtime->siteAddress;
	}}

	public readonly Services $services;

	abstract public Application $application {get;}

	public function __construct(public readonly Runtime $runtime)
	{
		$this->services = new Services()
			->setSession($this->runtime->session)
			->setSmtpServer($this->runtime->smtpServer)
			->setCallbackManager($this->runtime->callbackManager)
			->setFileManager($this->runtime->fileManager)
		;
	}
}
