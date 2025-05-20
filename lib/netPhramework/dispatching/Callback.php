<?php

namespace netPhramework\dispatching;

use netPhramework\common\Variables;

readonly class Callback implements Dispatcher
{
	public function __construct(private Path $path,
								private Variables $parameters) {}

	public function dispatch(Dispatchable $location): void
	{
		$location->getPath()->clear()->append($this->path);
		$location->getParameters()->clear()->merge($this->parameters);
		$location->seeOther();
	}
}