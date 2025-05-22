<?php

namespace netPhramework\dispatching;

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