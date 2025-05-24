<?php

namespace netPhramework\core;

use netPhramework\common\Variables;
use netPhramework\dispatching\Path;
use netPhramework\responding\ResponseInterface;

readonly class Request
{
	public function __construct(
		private Path $path,
		private Variables $parameters,
		private Socket $socket) {}

	/**
	 * @param RequestContext $context
	 * @return ResponseInterface
	 * @throws \Exception
	 */
	public function process(RequestContext $context):ResponseInterface
	{
		return $this->socket
			->processRequest($this->path, $this->parameters, $context);
	}
}