<?php

namespace netPhramework\dispatching\interfaces;

/**
 * The most basic ReadableLocation interface. Provides ReadablePath and iterable
 * Parameters.
 *
 */
interface ReadableLocation
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