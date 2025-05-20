<?php

namespace netPhramework\dispatching;

interface Location
{
    public function getPath():ReadablePath;
    public function getParameters():iterable;
}