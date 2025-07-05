<?php

namespace netPhramework\core;

use netPhramework\locating\Location;
use netPhramework\responding\Response;

readonly class Request
{
	public function __construct(private Location $location,
								private Socket   $socket) {}

	/**
	 * @param RequestContext $context
	 * @return Response
	 * @throws \Exception
	 */
	public function process(RequestContext $context):Response
	{
		return $this->socket
			->processRequest($this->location, $context);
	}
}