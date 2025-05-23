<?php

namespace netPhramework\dispatching;

use netPhramework\common\Variables;

/**
 * Provides modifiable RelocatablePath and Parameters.
 * ReloctablePath can be modified, but not read (the inverse of ReadablePath)
 */
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