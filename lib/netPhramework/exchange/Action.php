<?php

namespace netPhramework\exchange;

use netPhramework\nodes\Node;
use netPhramework\routing\Location;

class Action
{
	private Exchange $exchange;

	public function __construct
	(
		private readonly Node $handler,
		private readonly Location $location
	) {}

	public function openExchange(Services $services):self
	{
		$this->exchange = new Exchange($this->location)
			->setSession($services->session)
			->setFileManager($services->fileManager)
			->setSmtpServer($services->smtpServer)
			->setCallbackManager($services->callbackManager)
			;
		return $this;
	}

	public function execute(string $siteAddress):Response
	{
		$this->exchange->siteAddress = $siteAddress;
		$this->handler->handleExchange($this->exchange);
		return $this->exchange->response;
	}
}