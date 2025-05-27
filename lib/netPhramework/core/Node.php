<?php

namespace netPhramework\core;

use netPhramework\exceptions\ComponentNotFound;
use netPhramework\exceptions\InvalidUri;

interface Node
{
    /**
     * @param Exchange $exchange
	 * @throws InvalidUri
     * @return void
     */
	public function handleExchange(Exchange $exchange):void;

	/**
	 * @param string $name
	 * @return Node
	 * @throws ComponentNotFound
     * @throws Exception
	 */
	public function getChild(string $name):Node;

	/**
	 * @return string
	 */
	public function getName():string;
}