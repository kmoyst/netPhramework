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
		$this->exchange = new Exchange($this->location);
		$this->exchange->siteAddress = $services->siteAddress;
		$this->exchange->session = $services->session;
		$this->exchange->fileManager = $services->fileManager;
		$this->exchange->callbackManager = $services->callbackManager;
		$this->exchange->smtpServer = $services->smtpServer;
		return $this;
	}

	public function execute():Response
	{
		$this->handler->handleExchange($this->exchange);
		return $this->exchange->response;
	}
}