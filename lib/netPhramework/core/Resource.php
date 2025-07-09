<?php

namespace netPhramework\core;

use netPhramework\exceptions\ResourceNotFound;

interface Resource
{
	/**
	 * @param string $id
	 * @return Resource
	 * @throws ResourceNotFound
	 */
	public function getChild(string $id):Resource;
	public function getName():string;
	public function getResourceId():string;
	public function handleExchange(Exchange $exchange):void;
}