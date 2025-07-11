<?php

namespace netPhramework\exchange;

use netPhramework\routing\LocationFromUri;

class ActiveProcess extends RequestProcess
{
	public function exchange(RequestContext $context):Response
	{
		$location = new LocationFromUri($context->environment->uri);
		$location->getParameters()
			->clear()
			->merge($context->environment->postParameters);
		$exchange = new RequestExchange($location, $context);
		return $this->node->handleExchange($exchange);
	}
}