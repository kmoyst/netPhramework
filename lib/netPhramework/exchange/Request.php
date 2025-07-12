<?php

namespace netPhramework\exchange;

use netPhramework\bootstrap\Environment;
use netPhramework\routing\Location;
use netPhramework\routing\LocationFromUri;

class Request
{
	public readonly Exchange $exchange;
	public readonly Location $location;
	private(set) Environment $environment
	{
		get{ return $this->context->environment; }
		set{}
	}

	public function __construct(private readonly RequestContext $context)
	{
		$this->location = new LocationFromUri($context->environment->uri);
		$this->exchange = new Exchange($this->location, $context);
	}
}