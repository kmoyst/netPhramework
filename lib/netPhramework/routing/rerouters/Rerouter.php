<?php

namespace netPhramework\routing\rerouters;

use netPhramework\exceptions\PathException;
use netPhramework\routing\Path;
use netPhramework\routing\Reroutable;

readonly abstract class Rerouter
{
	public function __construct(protected Path|string $subPath = '') {}

	/**
	 * @param Reroutable $path
	 * @return void
	 * @throws PathException
	 */
    abstract public function reroute(Reroutable $path):void;

	/**
	 * @param Reroutable $path
	 * @return void
	 * @throws PathException
	 */
	protected function parseAndAppendSubPath(Reroutable $path):void
	{
		if(is_string($this->subPath))
			$path->appendName($this->subPath);
		else
			$path->appendPath($this->subPath);
	}
}