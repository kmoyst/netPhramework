<?php

namespace netPhramework\locating\rerouters;

use netPhramework\locating\Reroutable;

readonly class RerouteToChild extends Rerouter
{
	public function reroute(Reroutable $path): void
	{
		$path->append($this->subPath);
	}
}