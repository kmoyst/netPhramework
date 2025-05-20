<?php

namespace netPhramework\dispatching;

readonly class DispatchToRoot implements Dispatcher
{
	public function dispatch(Dispatchable $location): void
	{
		$location->getPath()->clear()->append('');
		$location->getParameters()->clear();
		$location->seeOther();
	}
}