<?php

namespace netPhramework\dispatching\rerouters;

use netPhramework\dispatching\Reroutable;

readonly class RerouteToParent extends Rerouter
{
    public function reroute(Reroutable $path): void
    {
        $path->pop()->pop()->append($this->subPath);
    }
}