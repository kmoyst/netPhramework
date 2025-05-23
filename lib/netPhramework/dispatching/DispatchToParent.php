<?php

namespace netPhramework\dispatching;

readonly class DispatchToParent extends Dispatcher
{
	public function __construct(private string $leafName = '') {}

	public function dispatch(Dispatchable $dispatchable): void
	{
        $relocator = new RelocateToParent($this->leafName);
        $dispatchable->seeOther();
	}
}