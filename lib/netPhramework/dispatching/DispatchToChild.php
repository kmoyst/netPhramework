<?php

namespace netPhramework\dispatching;

readonly class DispatchToChild implements Dispatcher
{
	/**
	 * @param string $leafName
	 */
	public function __construct(private string $leafName) {}

	public function dispatch(Dispatchable $dispatchable): void
	{
		$dispatchable->getPath()->append($this->leafName);
		$dispatchable->seeOther();
	}
}