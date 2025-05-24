<?php

namespace netPhramework\dispatching\relocators;

use netPhramework\dispatching\RelocatablePath;

readonly class RelocateToParent extends Relocator
{
    public function relocate(RelocatablePath $path): void
    {
        $path->pop()->pop()->append($this->subPath);
    }
}