<?php

namespace netPhramework\dispatching;

interface Dispatcher
{
	public function dispatch(Dispatchable $location):void;
}