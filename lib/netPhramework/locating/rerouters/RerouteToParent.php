<?php

namespace netPhramework\locating\rerouters;

use netPhramework\locating\Reroutable;

readonly class RerouteToParent extends Rerouter
{
    public function reroute(Reroutable $path): void
    {
        $path->pop()->pop()->append($this->subPath);
    }
}