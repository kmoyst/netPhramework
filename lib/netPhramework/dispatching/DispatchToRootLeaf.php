<?php

namespace netPhramework\dispatching;

readonly class DispatchToRootLeaf
	extends RelocateToRootLeaf implements Dispatcher
{
	public function dispatch(Dispatchable $dispatchable): void
	{
        $this->relocate($dispatchable);
        $dispatchable->seeOther();
	}
}