<?php

namespace netPhramework\locating;

use netPhramework\common\Variables;

abstract class Location extends ReadableLocation
{
	abstract public function getPath(): MutablePath;
	abstract public function getParameters(): Variables;
}