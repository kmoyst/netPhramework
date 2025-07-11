<?php

namespace netPhramework\exchange;

use netPhramework\locating\LocationFromUri;

class PassiveProcess extends RequestProcess
{
	public function exchange(RequestContext $context):Response
	{
		$location = new LocationFromUri($context->environment->uri);
		$exchange = new RequestExchange($location, $context);
		return $this->node->handleExchange($exchange);
	}
}