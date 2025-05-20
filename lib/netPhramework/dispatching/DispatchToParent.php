<?php

namespace netPhramework\dispatching;

readonly class DispatchToParent extends RelocateToParent implements Dispatcher
{
	public function dispatch(Dispatchable $exchange): void
	{
        $this->relocate($exchange);
        $exchange->seeOther();
	}
}