<?php

namespace netPhramework\locating\rerouters;

use netPhramework\locating\MutablePath;
use netPhramework\locating\Reroutable;

readonly abstract class Rerouter
{
	public function __construct(protected MutablePath|string $subPath = '') {}

	/**
	 * @param Reroutable $path
	 * @return void
	 */
    abstract public function reroute(Reroutable $path):void;
}