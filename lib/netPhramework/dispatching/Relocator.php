<?php

namespace netPhramework\dispatching;

interface Relocator
{
    /**
     * Modifies a Relocatable location. ReloctablePaths cannot be read.
     *
     * @param Relocatable $location
     * @return void
     */
    public function relocate(Relocatable $location):void;
}