<?php

namespace netPhramework\core;

use netPhramework\exceptions\NodeNotFound;

abstract class Node
{
	/**
	 * @param string $name
	 * @return Node
	 * @throws NodeNotFound
	 */
	abstract public function getChild(string $name):Node;
	abstract public function handleExchange(Exchange $exchange):void;
	abstract public function getComponentName():string;
	abstract public function getName():string;
}