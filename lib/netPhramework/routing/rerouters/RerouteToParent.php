<?php

namespace netPhramework\routing\rerouters;

use netPhramework\routing\Reroutable;

readonly class RerouteToParent extends Rerouter
{
    public function reroute(Reroutable $path): void
    {
        $path->pop()->pop()->append($this->subPath);
    }
}