<?php

namespace netPhramework\dispatching\relocators;

use netPhramework\dispatching\RelocatablePath;

readonly class RelocateToChild extends Relocator
{
	public function relocate(RelocatablePath $path): void
	{
		$path->append($this->subPath);
	}
}