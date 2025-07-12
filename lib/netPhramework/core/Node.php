<?php

namespace netPhramework\core;

use netPhramework\exceptions\ResourceNotFound;
use netPhramework\exchange\Exchange;
use netPhramework\resources\Directory;

class Node
{
	public Directory $root;

	public function __construct()
	{
		$this->root = new Directory('');
	}

	/**
	 * @param Exchange $exchange
	 * @return void
	 * @throws ResourceNotFound
	 */
    public function handleExchange(Exchange $exchange):void
	{
		new Navigator()
			->setRoot($this->root)
			->setPath($exchange->path)
			->navigate()
			->handleExchange($exchange)
		;
	}
}