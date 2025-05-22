<?php

namespace netPhramework\dispatching;

/**
 * Dispatches an exchange, changing the Location and preparing Response
 */
interface Dispatcher
{
	/**
	 * @param Dispatchable $dispatchable
	 * @return void
	 */
	public function dispatch(Dispatchable $dispatchable):void;
}