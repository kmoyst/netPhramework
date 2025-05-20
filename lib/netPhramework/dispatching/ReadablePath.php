<?php

namespace netPhramework\dispatching;

/**
 * Path can be read, but not changed.
 */
interface ReadablePath
{
    public function getName():?string;
    public function getNext():?ReadablePath;
}