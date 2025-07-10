<?php

namespace netPhramework\core;

use netPhramework\locating\LocationFromUri;
use netPhramework\locating\Location;

readonly class RequestInterpreter
{
	public function __construct(private RequestEnvironment $environment) {}

	/**
	 * @param Site $site
	 * @return Request
	 * @throws Exception
	 */
	public function establishRequest(Site $site):Request
	{
		$location = new LocationFromUri($this->environment->uri);
		return $this->createRequest($site, $location);
	}

	/**
	 * @param Site $site
	 * @param Location $location
	 * @return Request
	 * @throws Exception
	 */
	private function createRequest(
		Site $site, Location $location):Request
	{
		if(($postParameters = $this->environment->postParameters) !== null)
		{
			$socket = $site->openActiveSocket();
			$location->getParameters()->clear()->merge($postParameters);
		}
		else
		{
			$socket = $site->openPassiveSocket();
		}
		return new Request($location, $socket);
	}
}