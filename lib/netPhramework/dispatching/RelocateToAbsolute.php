<?php

namespace netPhramework\dispatching;

use netPhramework\common\Variables;

readonly class RelocateToAbsolute implements Relocator
{
    public function __construct(protected Path $path,
                                protected Variables $parameters) {}

    public function relocate(Relocatable $location): void
    {
        $location->getPath()->clear()->append($this->path);
        $location->getParameters()->clear()->merge($this->parameters);
    }
}