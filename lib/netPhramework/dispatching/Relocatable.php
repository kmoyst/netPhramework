<?php

namespace netPhramework\dispatching;

use netPhramework\common\Variables;

interface Relocatable
{
	/**
	 * Returns modifiable Path.
	 *
	 * @return RelocatablePath
	 */
    public function getPath():RelocatablePath;

	/**
	 * Returns modifiable Location parameters.
	 *
	 * @return Variables
	 */
    public function getParameters():Variables;
}