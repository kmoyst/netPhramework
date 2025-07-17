<?php

namespace netPhramework\routing\rerouters;

use netPhramework\routing\Reroutable;

readonly class RerouteToSibling extends Rerouter
{
    public function reroute(Reroutable $path): void
    {
		$this->parseAndAppendSubPath($path);
    }
}