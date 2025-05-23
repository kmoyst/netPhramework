<?php

namespace netPhramework\dispatching;

readonly class DispatchToRootLeaf extends Dispatcher
{
	public function __construct(private string $leafName = '') {}

	public function dispatch(Dispatchable $dispatchable): void
	{
		$relocator = new RelocateToRootLeaf($this->leafName);
		$relocator->relocate($dispatchable);
        $dispatchable->seeOther();
	}
}