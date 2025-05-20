<?php

namespace netPhramework\dispatching;

readonly class DispatchToParent implements Dispatcher
{
	public function __construct(private string $leafName = '') {}

	public function dispatch(Dispatchable $location): void
	{
		$location->getPath()->pop()->pop()->append($this->leafName);
		$location->getParameters()->clear();
		$location->seeOther();
	}
}