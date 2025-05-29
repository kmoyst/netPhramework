<?php

namespace netPhramework\core;

use netPhramework\common\Variables;
use netPhramework\dispatching\MutablePath;
use netPhramework\responding\Response;

readonly class Request
{
	public function __construct(
		private MutablePath $path,
		private Variables   $parameters,
		private Socket      $socket) {}

	/**
	 * @param RequestContext $context
	 * @return Response
	 * @throws \Exception
	 */
	public function process(RequestContext $context):Response
	{
		return $this->socket
			->processRequest($this->path, $this->parameters, $context);
	}
}