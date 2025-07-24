<?php

namespace netPhramework\core;

use netPhramework\routing\Path;
use netPhramework\routing\PathFromFilePath;

abstract class RuntimeContext
{
	public Path $fileRoot {get{
		return new PathFromFilePath(
			$this->get(RuntimeKey::FILE_ROOT->value));
	}}

	abstract public function get(string $key):?string;
}