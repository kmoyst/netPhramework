<?php

namespace netPhramework\dispatching;

readonly class RelocateToRoot implements Relocator
{
	public function __construct(private string $leafName = '') {}

    public function relocate(Relocatable $location): void
    {
        $location->getPath()->clear()->append($this->leafName);
        $location->getParameters()->clear();
    }
}