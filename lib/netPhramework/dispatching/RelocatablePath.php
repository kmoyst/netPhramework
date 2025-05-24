<?php

namespace netPhramework\dispatching;

/**
 * ReloctablePath can be modified, but not read (the inverse of ReadablePath)
 */
interface RelocatablePath
{
	public function append(Path|string $tail):RelocatablePath;
	public function pop():RelocatablePath;
	public function clear():RelocatablePath;
}