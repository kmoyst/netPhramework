<?php

namespace netPhramework\routing\rerouters;

use netPhramework\routing\Reroutable;

readonly class RerouteToRoot extends Rerouter
{
    public function reroute(Reroutable $path): void
    {
        $path->clear()->append($this->subPath);
    }
}