<?php

namespace netPhramework\dispatching\relocators;

use netPhramework\dispatching\interfaces\RelocatablePath;

readonly class RelocateToRoot extends Relocator
{
    public function relocate(RelocatablePath $path): void
    {
        $path->clear()->append($this->subPath);
    }
}