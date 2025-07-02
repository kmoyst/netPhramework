<?php

namespace netPhramework\locating;

use netPhramework\common\Variables;
use netPhramework\rendering\Encodable;

interface MutableLocation extends Encodable
{
	public function getPath(): MutablePath;
	public function getParameters(): Variables;
//	public function __clone(): void;
}