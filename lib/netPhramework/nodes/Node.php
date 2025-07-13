<?php

namespace netPhramework\nodes;

use netPhramework\exceptions\NodeNotFound;
use netPhramework\exchange\Exchange;

interface Node
{
	/**
	 * @param string $id
	 * @return Node
	 * @throws NodeNotFound
	 */
	public function getChild(string $id):Node;
	public function getName():string;
	public function getResourceId():string;
	public function handleExchange(Exchange $exchange):void;
}