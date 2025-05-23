<?php

namespace netPhramework\dispatching;

readonly abstract class Relocator
{
    /**
     * Modifies a Relocatable location. ReloctablePaths cannot be read.
     *
     * @param Relocatable $location
     * @return void
     */
    abstract public function relocate(Relocatable $location):void;
}