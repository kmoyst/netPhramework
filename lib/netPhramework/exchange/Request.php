<?php

namespace netPhramework\exchange;

use netPhramework\core\Socket;
use netPhramework\locating\Location;
use netPhramework\responding\Response;

readonly class Request
{
	public function __construct
	(
	private Socket  $socket,
	public Location $location
	)
	{}

	/**
	 * @param ExchangeContext $context
	 * @return Response
	 * @throws \Exception
	 */
	public function process(ExchangeContext $context):Response
	{
		$exchange = new RequestExchange($this->location, $context);
		return $this->socket->processRequest($exchange);
	}
}