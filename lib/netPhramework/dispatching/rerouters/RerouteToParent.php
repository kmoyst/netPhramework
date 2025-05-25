<?php

namespace netPhramework\dispatching\rerouters;

use netPhramework\dispatching\Reroutable;

readonly class RerouteToParent extends Rerouter
{
    public function relocate(Reroutable $path): void
    {
        $path->pop()->pop()->append($this->subPath);
    }
}