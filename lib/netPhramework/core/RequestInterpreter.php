<?php

namespace netPhramework\core;

use netPhramework\locating\LocationFromUri;
use netPhramework\locating\Location;

readonly class RequestInterpreter
{
	public function __construct(private RequestEnvironment $environment) {}

	/**
	 * @param Application $application
	 * @return Request
	 */
	public function establishRequest(Application $application):Request
	{
		$location = new LocationFromUri($this->environment->getUri());
		return $this->createRequest($application, $location);
	}

	/**
	 * @param Application $application
	 * @param Location $location
	 * @return Request
	 */
	private function createRequest(
		Application $application, Location $location):Request
	{
		if(($postParameters = $this->environment->getPostParameters()) !== null)
		{
			$socket = $application->openActiveSocket();
			$location->getParameters()->clear()->merge($postParameters);
		}
		else
		{
			$socket = $application->openPassiveSocket();
		}
		return new Request($location, $socket);
	}
}