<?php

namespace netPhramework\dispatching;

readonly class RelocateToRoot implements Relocator
{
    public function relocate(Relocatable $location): void
    {
        $location->getPath()->clear()->append('');
        $location->getParameters()->clear();
    }
}