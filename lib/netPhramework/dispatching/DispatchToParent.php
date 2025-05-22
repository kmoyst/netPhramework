<?php

namespace netPhramework\dispatching;

readonly class DispatchToParent extends RelocateToParent implements Dispatcher
{
	public function dispatch(Dispatchable $dispatchable): void
	{
        $this->relocate($dispatchable);
        $dispatchable->seeOther();
	}
}