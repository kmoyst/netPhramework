<?php

namespace netPhramework\core;

use netPhramework\locating\LocationFromUri;
use netPhramework\locating\MutableLocation;

class RequestInterpreter
{
	private RequestInput $input;

	public function __construct(?RequestInput $input = null)
	{
		$this->input = $input ?? new RequestInput();
	}

	/**
	 * @param Application $application
	 * @return Request
	 */
	public function establishRequest(Application $application):Request
	{
		$location = new LocationFromUri($this->input->getUri());
		return $this->createRequest($application, $location);
	}

	/**
	 * @param Application $application
	 * @param MutableLocation $location
	 * @return Request
	 */
	private function createRequest(
		Application $application, MutableLocation $location):Request
	{
		if(($postParameters = $this->input->getPostParameters()) !== null)
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