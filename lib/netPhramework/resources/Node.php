<?php

namespace netPhramework\resources;

use netPhramework\exceptions\ResourceNotFound;
use netPhramework\exchange\Exchange;

interface Node
{
	/**
	 * @param string $id
	 * @return Node
	 * @throws ResourceNotFound
	 */
	public function getChild(string $id):Node;
	public function getName():string;
	public function getResourceId():string;
	public function handleExchange(Exchange $exchange):void;
}