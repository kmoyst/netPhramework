<?php

namespace netPhramework\exchange;

use netPhramework\core\Application;
use netPhramework\core\Site;
use netPhramework\exceptions\Exception;
use netPhramework\exceptions\NodeNotFound;
use netPhramework\nodes\Node;

class Router
{
	public Request $request;
	private Node $root;
	private Node $handler;

	/**
	 * @param Application $application
	 * @return $this
	 * @throws Exception
	 */
	public function routeThrough(Application $application):self
	{
		$this->root = $this->request->routeThrough($application)->andGetNode();
		return $this;
	}

	/**
	 * @return $this
	 * @throws NodeNotFound
	 */
	public function navigateToHandler():self
	{
		$this->handler = new Navigator()
			->setRoot($this->root)
			->setPath($this->request->location->path)
			->navigate();
		return $this;
	}

	public function andGetResponseForDeliveryTo(Site $site):Response
	{
		$exchange = new Exchange($this->request->location, $site);
		$this->handler->handleExchange($exchange);
		return $exchange->response;
	}
}