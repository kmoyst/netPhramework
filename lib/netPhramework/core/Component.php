<?php

namespace netPhramework\core;

use netPhramework\exceptions\ComponentNotFound;

interface Component
{

    /**
     * @param Exchange $exchange
     * @return void
     */
	public function handleExchange(Exchange $exchange):void;

	/**
	 * @param string $name
	 * @return Component
	 * @throws ComponentNotFound
     * @throws Exception
	 */
	public function getChild(string $name):Component;

	/**
	 * @return string
	 */
	public function getName():string;
}