<?php

namespace netPhramework\dispatching\interfaces;

use netPhramework\dispatching\Path;

/**
 * ReloctablePath can be modified, but not read (the inverse of ReadablePath)
 */
interface RelocatablePath
{
	public function append(Path|string $tail):RelocatablePath;
	public function pop():RelocatablePath;
	public function clear():RelocatablePath;
}