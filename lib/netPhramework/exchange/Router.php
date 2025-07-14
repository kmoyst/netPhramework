<?php

namespace netPhramework\exchange;

use netPhramework\core\Application;
use netPhramework\core\Environment;
use netPhramework\exceptions\Exception;
use netPhramework\exceptions\NodeNotFound;
use netPhramework\nodes\Node;

class Router
{
	private Request $request;
	private Response $response;
	private Node $handler;

	public function __construct(private readonly Environment $environment) {}

	public function retrieveRequest(Interpreter $interpreter):self
	{
		$this->request = $interpreter->interpret($this->environment);
		return $this;
	}

	/**
	 * @param Application $application
	 * @return $this
	 * @throws Exception
	 */
	public function andRouteInto(Application $application):self
	{
		$this->request
			->setEnvironment($this->environment)
			->dispatch($application)
		;
		return $this;
	}

	/**
	 * @return $this
	 * @throws NodeNotFound
	 */
	public function toFindHandler():self
	{
		$this->handler = new Navigator()
			->setRoot($this->request->root)
			->setPath($this->request->location->getPath())
			->navigate();
		return $this;
	}

	public function andProcessExchange(Services $services):self
	{
		$exchange = new Exchange($services)
			->setEnvironment($this->environment)
			->setLocation($this->request->location)
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