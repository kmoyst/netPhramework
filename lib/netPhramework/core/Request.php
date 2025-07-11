<?php

namespace netPhramework\core;

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
	 * @param RequestContext $context
	 * @return Response
	 * @throws \Exception
	 */
	public function process(RequestContext $context):Response
	{
		$exchange = new SocketExchange($this->location, $context);
		return $this->socket->processRequest($exchange);
	}
}