<?php

namespace netPhramework\locating\rerouters;

use netPhramework\locating\MutablePath;
use netPhramework\locating\Reroutable;

readonly class RerouteToNephew extends Rerouter
{
	public function __construct(
		protected string $siblingName,
		string|MutablePath $subPath = ''
	)
	{
		parent::__construct($subPath);
	}

	public function reroute(Reroutable $path): void
	{
		$path->pop()->append($this->siblingName)->append($this->subPath);
	}
}