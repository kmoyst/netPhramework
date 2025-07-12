<?php

namespace netPhramework\exchange;

use netPhramework\exceptions\ResourceNotFound;
use netPhramework\resources\Directory;

class Site
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