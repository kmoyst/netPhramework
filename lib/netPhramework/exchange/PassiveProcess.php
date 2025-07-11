<?php

namespace netPhramework\exchange;

use netPhramework\locating\LocationFromUri;

class PassiveProcess extends RequestProcess
{
	public function request(RequestContext $context):Request
	{
		$location = new LocationFromUri($context->environment->uri);
		$exchange = new RequestExchange($location, $context);
		return new Request($this->node, $exchange);
	}
}