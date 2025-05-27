<?php

namespace netPhramework\dispatching\rerouters;

use netPhramework\dispatching\Reroutable;

readonly class RerouteToRoot extends Rerouter
{
    public function reroute(Reroutable $path): void
    {
        $path->clear()->append($this->subPath);
    }
}