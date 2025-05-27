<?php

namespace netPhramework\dispatching\rerouters;

use netPhramework\dispatching\MutablePath;
use netPhramework\dispatching\Reroutable;

readonly abstract class Rerouter
{
	public function __construct(protected MutablePath|string $subPath = '') {}

	/**
	 * @param Reroutable $path
	 * @return void
	 */
    abstract public function reroute(Reroutable $path):void;
}