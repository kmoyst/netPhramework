<?php

namespace netPhramework\core;

use netPhramework\locating\ConfigurableLocation;
use netPhramework\locating\LocationFromUri;

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
	 * @param ConfigurableLocation $location
	 * @return Request
	 */
	private function createRequest(
		Application $application, ConfigurableLocation $location):Request
	{
		if($this->input->getPostParameters() === null)
		{
			$socket = $application->openPassiveSocket();
			$location->setParameters($this->input->getQueryParameters() ?? []);
		}
		else
		{
			$socket = $application->openActiveSocket();
			$location->setParameters($this->input->getPostParameters());
		}
		return new Request($location, $socket);
	}
}