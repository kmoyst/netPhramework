<?php

namespace netPhramework\dispatching;

/**
 * Basic Path interface. Can be read / traversed, but not modified.
 *
 */
interface ReadablePath
{
    public function getName():?string;
    public function getNext():?ReadablePath;
}