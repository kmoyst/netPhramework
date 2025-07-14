<?php

namespace netPhramework\exchange;

use netPhramework\core\Environment;
use netPhramework\exceptions\PathException;
use netPhramework\routing\LocationFromUri;

class HttpInterpreter implements Interpreter
{
	/**
	 * @param Environment $environment
	 * @return Request
	 * @throws PathException
	 */
	public function interpret(Environment $environment):Request
	{
		if($environment->postParameters === null)
			$request = new PassiveRequest();
		else
			$request = new ActiveRequest();
		return $request
			->setLocation(new LocationFromUri($environment->uri)->initialize());
	}
}