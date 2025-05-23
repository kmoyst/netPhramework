<?php

namespace netPhramework\dispatching;

readonly class DispatchToChild implements Dispatcher
{
	public function __construct(
        private string $leafName,
        private bool $preserveParameters = true) {}

	public function dispatch(Dispatchable $dispatchable): void
	{
		$dispatchable->getPath()->append($this->leafName);
        if(!$this->preserveParameters)
            $dispatchable->getParameters()->clear();
		$dispatchable->seeOther();
	}
}