<?php

namespace netPhramework\routing\rerouters;

use netPhramework\routing\MutablePath;
use netPhramework\routing\Reroutable;

readonly abstract class Rerouter
{
	public function __construct(protected MutablePath|string $subPath = '') {}

	/**
	 * @param Reroutable $path
	 * @return void
	 */
    abstract public function reroute(Reroutable $path):void;
}