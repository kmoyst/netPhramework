<?php

namespace netPhramework\dispatching;

readonly class RelocateToSibling extends Relocator
{
    public function __construct(protected string $leafName = '') {}

    public function relocate(Relocatable $location): void
    {
        $location->getPath()->pop()->append($this->leafName);
        $location->getParameters()->clear();
    }
}