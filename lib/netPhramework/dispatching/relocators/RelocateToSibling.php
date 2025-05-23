<?php

namespace netPhramework\dispatching\relocators;

use netPhramework\dispatching\interfaces\RelocatablePath;

readonly class RelocateToSibling extends Relocator
{
    public function relocate(RelocatablePath $path): void
    {
        $path->pop()->append($this->subPath);
    }
}