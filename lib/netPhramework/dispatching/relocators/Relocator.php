<?php

namespace netPhramework\dispatching\relocators;

use netPhramework\dispatching\Path;
use netPhramework\dispatching\RelocatablePath;

readonly abstract class Relocator
{
	public function __construct(protected Path|string $subPath = '') {}

	/**
	 * @param RelocatablePath $path
	 * @return void
	 */
    abstract public function relocate(RelocatablePath $path):void;
}