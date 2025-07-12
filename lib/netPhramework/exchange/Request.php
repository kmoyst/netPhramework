<?php

namespace netPhramework\exchange;

use netPhramework\exceptions\Exception;
use netPhramework\exceptions\ResourceNotFound;
use netPhramework\routing\LocationFromUri;

readonly class Request
{
	public function __construct(protected RequestStrategy $strategy) {}

	/**
	 * @param RequestContext $context
	 * @return Response
	 * @throws Exception
	 * @throws ResourceNotFound
	 */
	public function process(RequestContext $context):Response
	{
		$location = new LocationFromUri($context->environment->uri);
		$exchange = new Exchange($location, $context);
		$handler  = new ExchangeHandler();
		;
		$this->strategy
			->setApplication($context->getApplication())
			->setLocation($location)
			->setEnvironment($context->environment)
			->configure($handler);
		$handler->handleExchange($exchange);
		return $exchange->response;
	}
}