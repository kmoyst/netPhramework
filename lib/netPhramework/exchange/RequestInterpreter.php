<?php

namespace netPhramework\exchange;

use netPhramework\core\Site;
use netPhramework\exceptions\Exception;

readonly class RequestInterpreter
{
	public function __construct(private RequestEnvironment $environment) {}

	/**
	 * @param Site $application
	 * @return RequestProcess
	 * @throws Exception
	 */
	public function interpret(Site $application):RequestProcess
	{
		if($this->environment->postParameters === null)
			return new PassiveProcess($application->openPassiveNode());
		else
			return new ActiveProcess($application->openActiveNode());
	}
}