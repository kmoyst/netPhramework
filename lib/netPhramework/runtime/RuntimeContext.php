<?php

namespace netPhramework\runtime;

use netPhramework\routing\Path;
use netPhramework\routing\PathFromDisk;

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
		return new PathFromDisk(
			$this->input->get(RuntimeKey::FILE_ROOT->value));
	}}
}