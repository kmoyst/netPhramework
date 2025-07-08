<?php

namespace netPhramework\core;

use netPhramework\exceptions\NodeNotFound;

abstract class Node
{
	/**
	 * Returns the Node's Id
	 *
	 * @return string
	 */
	public function getNodeId():string
	{
		return $this->getName();
	}

	/**
	 * Retrieves a child node
	 *
	 * @param string $id
	 * @return Node
	 * @throws NodeNotFound
	 */
	abstract public function getChild(string $id):Node;

	/**
	 * Handles the Request / Response Exchange
	 *
	 * @param Exchange $exchange
	 * @return void
	 */
	abstract public function handleExchange(Exchange $exchange):void;

	/**
	 * Returns the Node's Name
	 *
	 * @return string
	 */
	abstract public function getName():string;
}