<?php

namespace netPhramework\dispatching;

readonly class DispatchToRoot extends RelocateToRoot implements Dispatcher
{
	public function dispatch(Dispatchable $exchange): void
	{
        $this->relocate($exchange);
        $exchange->seeOther();
	}
}