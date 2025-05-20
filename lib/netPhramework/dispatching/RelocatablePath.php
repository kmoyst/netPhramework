<?php

namespace netPhramework\dispatching;

/**
 * Path can be changed, but not read.
 */
interface RelocatablePath
{
	public function append(Path|string $tail):RelocatablePath;
	public function pop():RelocatablePath;
	public function clear():RelocatablePath;
}