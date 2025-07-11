<?php

namespace netPhramework\core;

use netPhramework\exceptions\Exception;
use netPhramework\exchange\RequestExchange;
use netPhramework\resources\Directory;
use netPhramework\responding\Response;

readonly class Node
{
	public Directory $root;

	public function __construct()
	{
		$this->root = new Directory('');
	}

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