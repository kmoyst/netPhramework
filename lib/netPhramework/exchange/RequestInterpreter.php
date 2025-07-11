<?php

namespace netPhramework\exchange;

use netPhramework\site\Application;
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
			return new PassiveProcess($application->openPassiveNode());
		else
			return new ActiveProcess($application->openActiveNode());
	}
}