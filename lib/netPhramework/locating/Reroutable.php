<?php

namespace netPhramework\locating;

/**
 * ReloctablePath can be modified, but not read (the inverse of Path)
 */
interface Reroutable
{
	public function append(MutablePath|string $tail):Reroutable;
	public function pop():Reroutable;
	public function clear():Reroutable;
}