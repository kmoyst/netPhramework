<?php

namespace netPhramework\routing\rerouters;

use netPhramework\routing\MutablePath;
use netPhramework\routing\Reroutable;

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