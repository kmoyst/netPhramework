<?php

namespace netPhramework\dispatching;

readonly class DispatchToSibling extends RelocateToSibling implements Dispatcher
{
	public function dispatch(Dispatchable $exchange): void
	{
        $this->relocate($exchange);
        $exchange->seeOther();
	}
}