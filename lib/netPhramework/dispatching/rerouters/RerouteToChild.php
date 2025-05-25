<?php

namespace netPhramework\dispatching\rerouters;

use netPhramework\dispatching\Reroutable;

readonly class RerouteToChild extends Rerouter
{
	public function relocate(Reroutable $path): void
	{
		$path->append($this->subPath);
	}
}