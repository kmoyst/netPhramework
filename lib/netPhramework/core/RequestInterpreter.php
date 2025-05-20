<?php

namespace netPhramework\core;

use netPhramework\common\Variables;
use netPhramework\dispatching\UriAdapter;
use netPhramework\exceptions\Exception;

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
	 * @throws Exception
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
		return new Request(
			(new UriAdapter($this->requestInput->getUri()))->getPath(),
			(new Variables())->merge($parameters),
			$socket);
	}
}