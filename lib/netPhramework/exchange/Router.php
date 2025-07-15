<?php

namespace netPhramework\exchange;

use netPhramework\core\Application;
use netPhramework\core\Environment;
use netPhramework\exceptions\Exception;
use netPhramework\exceptions\NodeNotFound;
use netPhramework\nodes\Directory;
use netPhramework\nodes\Node;
use netPhramework\routing\Location;

class Router
{
	private(set) Response $response;
	private(set) Node $handler;
	private Directory $root;
	private Location $location;

	public function __construct(private readonly Application $application)
	{
		$this->root = new Directory('');
	}

	/**
	 * @param Request $request
	 * @return $this
	 * @throws Exception
	 */
	public function openRequest(Request $request):self
	{
		if(!$request->isModificationRequest())
			$this->application->configurePassiveNode($this->root);
		else
			$this->application->configureActiveNode($this->root)
		;
		$this->location = $request;
		return $this;
	}

	/**
	 * @return self
	 * @throws NodeNotFound
	 */
	public function andFindHandler():self
	{
		$this->handler = new Navigator()
			->setRoot($this->root)
			->setPath($this->location->path)
			->navigate()
		;
		return $this;
	}

	public function toProcessExchange(Environment $env, Services $services):self
	{
		$exchange = new Exchange($services)
			->setEnvironment($env)
			->setLocation($this->location)
		;
		$this->handler->handleExchange($exchange);
		$this->response = $exchange->response;
		return $this;
	}

	public function andDeliverResponseThrough(Responder $responder):void
	{
		$this->response->deliver($responder);
	}
}