<?php

namespace netPhramework\dispatching;

interface Relocator
{
    public function relocate(Relocatable $location):void;
}