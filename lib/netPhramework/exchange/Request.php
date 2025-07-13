<?php

namespace netPhramework\exchange;

use netPhramework\core\Site;
use netPhramework\exceptions\Exception;
use netPhramework\exceptions\ResourceNotFound;
use netPhramework\routing\LocationFromUri;

readonly class Request
{
	public function __construct(protected RequestStrategy $strategy) {}

	/**
	 * @param Site $site
	 * @return Response
	 * @throws Exception
	 * @throws ResourceNotFound
	 */
	public function process(Site $site):Response
	{
		$location = new LocationFromUri($site->environment->uri);
		$exchange = new Exchange($location, $site);
		$this->strategy
			->setSite($site)
			->setLocation($location)
			->prepare()
			->requestApplication()
			->handleExchange($exchange);
		return $exchange->response;
	}
}