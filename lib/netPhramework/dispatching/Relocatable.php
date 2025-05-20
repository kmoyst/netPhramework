<?php

namespace netPhramework\dispatching;

use netPhramework\common\Variables;

interface Relocatable
{
    public function getPath():RelocatablePath;
    public function getParameters():Variables;
}