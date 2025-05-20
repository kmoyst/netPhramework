<?php

namespace netPhramework\dispatching;

interface DispatchablePath
{
	public function append(Path|string $tail):DispatchablePath;
	public function pop():DispatchablePath;
	public function clear():DispatchablePath;
}