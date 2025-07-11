<?php

namespace netPhramework\site;

use netPhramework\exceptions\Exception;
use netPhramework\exchange\RequestExchange;
use netPhramework\exchange\Response;
use netPhramework\resources\Directory;

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
    public function handleExchange(RequestExchange $exchange):Response
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