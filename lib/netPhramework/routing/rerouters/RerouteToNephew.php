<?php

namespace netPhramework\routing\rerouters;

use netPhramework\routing\Path;
use netPhramework\routing\Reroutable;

readonly class RerouteToNephew extends Rerouter
{
	public function __construct(
		protected string $siblingName,
		string|Path $subPath = ''
	)
	{
		parent::__construct($subPath);
	}

	public function reroute(Reroutable $path): void
	{
		$path->pop()->appendName($this->siblingName);
		$this->parseAndAppendSubPath($path);
	}
}