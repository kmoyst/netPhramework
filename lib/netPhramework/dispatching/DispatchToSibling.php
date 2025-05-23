<?php

namespace netPhramework\dispatching;

readonly class DispatchToSibling extends Dispatcher
{
	public function __construct(protected string $leafName = '') {}

	public function dispatch(Dispatchable $dispatchable): void
	{
		$relocator = new RelocateToSibling($this->leafName);
		$relocator->relocate($dispatchable);
        $dispatchable->seeOther();
	}
}