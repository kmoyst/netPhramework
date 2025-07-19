<?php

namespace netPhramework\configuration;

use netPhramework\exchange\Host;
use netPhramework\exchange\host\HostContext;
use netPhramework\exchange\host\HostKey;
use netPhramework\exchange\host\HostMode;
use netPhramework\exchange\Request;
use netPhramework\exchange\Responder;
use netPhramework\exchange\Services;

abstract class Site
{
	public bool $inDevelopment {get{
		return $this->hostMode->isDevelopment();
	}}

	public HostMode $hostMode {get{
		return $this->host->mode;
	}}

	public Request $request {get{
		return $this->host->request;
	}}

	public string $address {get{
		return "{$this->host->protocol}://{$this->host->domain}}";
	}}

	public Responder $responder {get{
		return $this->host->responder;
	}}

	public Services $services {get{
		if(!isset($this->services))
		{
			$this->services = new Services()
				->setSession($this->host->session)
				->setCallbackManager($this->host->callbackManager)
				->setSmtpServer($this->host->smtpServer)
				->setFileManager($this->host->fileManager)
			;
		}
		return $this->services;
	}}

	abstract public Application $application {get;}

	public function __construct(private readonly Host $host) {}

	public function configure():void
	{

	}
}
