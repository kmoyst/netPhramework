<?php

namespace netPhramework\dispatching;

/**
 * Dispatches an exchange, changing the Location and preparing Response
 */
interface Dispatcher
{
	public function dispatch(Dispatchable $exchange):void;
}