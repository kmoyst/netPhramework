<?php

namespace netPhramework\core;

use netPhramework\exceptions\Exception;
use netPhramework\exchange\RequestExchange;
use netPhramework\resources\Resource;
use netPhramework\responding\Response;

readonly class Socket
{
	public function __construct(private Resource $root) {}

	/**
	 * @param RequestExchange $exchange
	 * @return Response
	 */
    public function processRequest(RequestExchange $exchange):Response
	{
        try
		{
			new Navigator()
				->setRoot($this->root)
				->setPath($exchange->path)
				->navigate()
				->handleExchange($exchange)
			;
			return $exchange->response;
		}
		catch (Exception $exception)
		{
			return $exception->setEnvironment($exchange->environment);
		}
	}
}