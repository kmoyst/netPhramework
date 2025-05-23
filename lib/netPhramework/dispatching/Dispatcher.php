<?php

namespace netPhramework\dispatching;

/**
 * Relocates and dispatches. Used to prepare redirect Responses.
 */
readonly abstract class Dispatcher
{
	/**
	 * @param Dispatchable $dispatchable
	 * @return void
	 */
	abstract public function dispatch(Dispatchable $dispatchable):void;
}