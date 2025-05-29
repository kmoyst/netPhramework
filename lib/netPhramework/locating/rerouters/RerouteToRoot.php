<?php

namespace netPhramework\locating\rerouters;

use netPhramework\locating\Reroutable;

readonly class RerouteToRoot extends Rerouter
{
    public function reroute(Reroutable $path): void
    {
        $path->clear()->append($this->subPath);
    }
}