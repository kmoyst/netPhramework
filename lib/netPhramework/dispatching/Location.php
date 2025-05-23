<?php

namespace netPhramework\dispatching;

/**
 * The most basic Location interface. Provides ReadablePath and iterable
 * Parameters.
 *
 */
interface Location
{
	/**
	 * Returns a readable Path.
	 *
	 * @return ReadablePath
	 */
    public function getPath():ReadablePath;

	/**
	 * Returns parameters for iteration.
	 *
	 * @return iterable
	 */
    public function getParameters():iterable;
}