<?php

namespace netPhramework\core;

use netPhramework\locating\MutableLocation;
use netPhramework\responding\Response;

readonly class Request
{
	public function __construct(private MutableLocation $location,
								private Socket $socket) {}

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