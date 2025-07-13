<?php

namespace netPhramework\exchange;

use netPhramework\core\Site;
use netPhramework\exceptions\Exception;
use netPhramework\exceptions\NodeNotFound;
use netPhramework\routing\LocationFromUri;

readonly class Request
{
	public function __construct(protected Dispatcher $dispatcher) {}

	/**
	 * @param Site $site
	 * @return Response
	 * @throws Exception
	 * @throws NodeNotFound
	 */
	public function process(Site $site):Response
	{
		$location = new LocationFromUri($site->environment->uri);
		$exchange = new Exchange($location, $site);
		$this->dispatcher
			->setSite($site)
			->setLocation($location)
			->prepare()
			->dispatch()
			->route($exchange->path)
			->handleExchange($exchange);
		return $exchange->response;
	}
}