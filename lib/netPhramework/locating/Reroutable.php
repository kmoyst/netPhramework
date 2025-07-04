<?php

namespace netPhramework\locating;

interface Reroutable
{
	public function append(Path|string $tail):Reroutable;
	public function pop():Reroutable;
	public function clear():Reroutable;
}