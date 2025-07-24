<?php

namespace netPhramework\core;

use netPhramework\routing\Path;
use netPhramework\routing\PathFromDiskPath;

class RuntimeContext
{
	public function __construct
	(
		private(set) RuntimeVariables $input
	)
	{

	}

	public function get(string $key):?string
	{
		return $this->input->get($key);
	}

	public Path $fileRoot {get{
		return new PathFromDiskPath(
			$this->input->get(RuntimeKey::FILE_ROOT->value));
	}}
}