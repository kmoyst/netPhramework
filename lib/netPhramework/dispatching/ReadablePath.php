<?php

namespace netPhramework\dispatching;

interface ReadablePath
{
    public function getName():?string;
    public function getNext():?ReadablePath;
}