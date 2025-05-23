<?php

namespace netPhramework\dispatching;

use netPhramework\common\Variables;

/**
 * Dispatches to a Location irrelevant of the $dispatchable's initial state
 */
readonly class DispatchToAbsolute extends Dispatcher
{
	public function __construct(protected Path $path,
								protected Variables $parameters) {}

	public function dispatch(Dispatchable $dispatchable): void
	{
		$relocator = new RelocateToAbsolute($this->path, $this->parameters);
		$relocator->relocate($dispatchable);
		$dispatchable->seeOther();
	}
}