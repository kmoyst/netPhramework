<?php

namespace netPhramework\core;

use netPhramework\routing\Path;
use netPhramework\routing\PathFromDiskPath;

abstract class RuntimeContext
{
	public Path $fileRoot {get{
		return new PathFromDiskPath(
			$this->get(RuntimeKey::FILE_ROOT->value));
	}}

	abstract public function get(string $key):?string;
}