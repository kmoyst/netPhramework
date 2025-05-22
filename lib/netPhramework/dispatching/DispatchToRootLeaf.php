<?php

namespace netPhramework\dispatching;

readonly class DispatchToRootLeaf
	extends RelocateToRootLeaf implements Dispatcher
{
	public function dispatch(Dispatchable $exchange): void
	{
        $this->relocate($exchange);
        $exchange->seeOther();
	}
}