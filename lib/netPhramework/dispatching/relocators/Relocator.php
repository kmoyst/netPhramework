<?php

namespace netPhramework\dispatching\relocators;

use netPhramework\dispatching\interfaces\RelocatablePath;
use netPhramework\dispatching\Path;

readonly abstract class Relocator
{
	public function __construct(protected Path|string $subPath = '') {}

	/**
	 * @param RelocatablePath $path
	 * @return void
	 */
    abstract public function relocate(RelocatablePath $path):void;
}