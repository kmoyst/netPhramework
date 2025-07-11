<?php

namespace netPhramework\exchange;

use netPhramework\core\Site;
use netPhramework\exceptions\Exception;
use netPhramework\locating\Location;
use netPhramework\locating\LocationFromUri;

readonly class RequestInterpreter
{
	public function __construct(private RequestEnvironment $environment) {}

	/**
	 * @param Site $site
	 * @return Request
	 * @throws Exception
	 */
	public function interpretRequest(Site $site):Request
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
	private function createRequest(Site $site, Location $location):Request
	{
		if(($postParameters = $this->environment->postParameters) !== null)
		{
			$socket = $site->openActiveNode();
			$location->getParameters()->clear()->merge($postParameters);
		}
		else
		{
			$socket = $site->openPassiveNode();
		}
		return new Request($socket, $location);
	}
}