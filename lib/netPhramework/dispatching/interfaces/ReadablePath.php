<?php

namespace netPhramework\dispatching\interfaces;

/**
 * Basic Path interface. Can be read / traversed, but not modified.
 *
 */
interface ReadablePath
{
    public function getName():?string;
    public function getNext():?ReadablePath;
}