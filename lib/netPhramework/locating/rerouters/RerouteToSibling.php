<?php

namespace netPhramework\locating\rerouters;

use netPhramework\locating\Reroutable;

readonly class RerouteToSibling extends Rerouter
{
    public function reroute(Reroutable $path): void
    {
        $path->pop()->append($this->subPath);
    }
}