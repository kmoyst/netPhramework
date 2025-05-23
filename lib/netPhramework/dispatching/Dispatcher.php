<?php

namespace netPhramework\dispatching;

/**
 * Relocates and dispatches. Used to prepare redirect Responses.
 */
interface Dispatcher
{
	/**
	 * @param Dispatchable $dispatchable
	 * @return void
	 */
	public function dispatch(Dispatchable $dispatchable):void;
}