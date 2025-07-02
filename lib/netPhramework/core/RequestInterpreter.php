<?php

namespace netPhramework\core;

use netPhramework\common\Variables;
use netPhramework\locating\LocationFromUri;

class RequestInterpreter
{
	private RequestInput $requestInput;

	public function __construct(?RequestInput $requestInput = null)
	{
		$this->requestInput = $requestInput ?? new RequestInput();
	}

	/**
	 * @param Application $application
	 * @return Request
	 */
	public function establishRequest(Application $application):Request
	{
		if($this->requestInput->getPostParameters() === null)
		{
			$socket = $application->openPassiveSocket();
			$parameters = $this->requestInput->getQueryParameters() ?? [];
		}
		else
		{
			$socket = $application->openActiveSocket();
			$parameters = $this->requestInput->getPostParameters();
		}
		return new Request(new LocationFromUri(
				$this->requestInput->getUri(),
				new Variables()->merge($parameters)), $socket);
	}
}