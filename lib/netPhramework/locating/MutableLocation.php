<?php

namespace netPhramework\locating;

use netPhramework\common\Variables;

abstract class MutableLocation extends Location
{
	abstract public function getPath(): MutablePath;
	abstract public function getParameters(): Variables;
}