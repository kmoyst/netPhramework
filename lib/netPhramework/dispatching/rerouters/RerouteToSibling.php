<?php

namespace netPhramework\dispatching\rerouters;

use netPhramework\dispatching\Reroutable;

readonly class RerouteToSibling extends Rerouter
{
    public function relocate(Reroutable $path): void
    {
        $path->pop()->append($this->subPath);
    }
}