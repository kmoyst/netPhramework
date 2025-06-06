<?php

namespace netPhramework\core;

use netPhramework\exceptions\NodeNotFound;

interface Node
{
	/**
	 * @param string $name
	 * @return Node
	 * @throws NodeNotFound
	 */
	public function getChild(string $name):Node;
	public function handleExchange(Exchange $exchange):void;
	public function getName():string;
}