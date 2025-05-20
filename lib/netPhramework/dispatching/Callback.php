<?php

namespace netPhramework\dispatching;

readonly class Callback extends RelocateToAbsolute implements Dispatcher
{
	public function dispatch(Dispatchable $exchange): void
	{
        $this->relocate($exchange);
		$exchange->seeOther();
	}
}