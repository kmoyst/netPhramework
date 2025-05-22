<?php

namespace netPhramework\dispatching;

readonly class DispatchToSibling extends RelocateToSibling implements Dispatcher
{
	public function dispatch(Dispatchable $dispatchable): void
	{
        $this->relocate($dispatchable);
        $dispatchable->seeOther();
	}
}