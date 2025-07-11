<?php

namespace netPhramework\core;

use netPhramework\responding\Response;

readonly class Socket
{
	public function __construct(private Resource $root) {}

	/**
	 * @param SocketExchange $exchange
	 * @return Response
	 */
    public function processRequest(SocketExchange $exchange):Response
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