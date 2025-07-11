<?php

namespace netPhramework\exchange;

use netPhramework\core\Application;
use netPhramework\exceptions\Exception;

readonly class RequestInterpreter
{
	public function __construct(private RequestEnvironment $environment) {}

	/**
	 * @param Application $application
	 * @return RequestProcess
	 * @throws Exception
	 */
	public function interpret(Application $application):RequestProcess
	{
		if($this->environment->postParameters === null)
			return new PassiveProcess($application->openActiveNode());
		else
			return new ActiveProcess($application->openPassiveNode());
	}
}