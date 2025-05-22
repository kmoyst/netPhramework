<?php

namespace netPhramework\dispatching;

readonly class DispatchToAbsolute extends RelocateToAbsolute implements Dispatcher
{
	public function dispatch(Dispatchable $dispatchable): void
	{
        $this->relocate($dispatchable);
		$dispatchable->seeOther();
	}
}