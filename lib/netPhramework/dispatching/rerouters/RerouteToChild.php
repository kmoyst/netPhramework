<?php

namespace netPhramework\dispatching\rerouters;

use netPhramework\dispatching\Reroutable;

readonly class RerouteToChild extends Rerouter
{
	public function reroute(Reroutable $path): void
	{
		$path->append($this->subPath);
	}
}