<?php

namespace netPhramework\core;

use netPhramework\exceptions\NodeNotFound;

interface Node
{
	/**
	 * Returns the Node's Id
	 *
	 * @return string
	 */
	public function getNodeId():string;
	/**
	 * Retrieves a child node
	 *
	 * @param string $id
	 * @return Node
	 * @throws NodeNotFound
	 */
	public function getChild(string $id):Node;

	/**
	 * Handles the Request / Response Exchange
	 *
	 * @param Exchange $exchange
	 * @return void
	 */
	public function handleExchange(Exchange $exchange):void;

	/**
	 * Returns the Node's Name
	 *
	 * @return string
	 */
	public function getName():string;
}