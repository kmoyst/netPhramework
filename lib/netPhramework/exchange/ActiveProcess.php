<?php

namespace netPhramework\exchange;

use netPhramework\locating\LocationFromUri;

class ActiveProcess extends RequestProcess
{
	public function request(RequestContext $context):Request
	{
		$location = new LocationFromUri($context->environment->uri);
		$location->getParameters()
			->clear()
			->merge($context->environment->postParameters);
		$exchange = new RequestExchange($location, $context);
		return new Request($this->node, $exchange);
	}
}