<?php

namespace netPhramework\exchange;

use netPhramework\core\Application;
use netPhramework\core\Context;
use netPhramework\core\Environment;
use netPhramework\exceptions\Exception;
use netPhramework\exceptions\NodeNotFound;
use netPhramework\nodes\Directory;
use netPhramework\nodes\Node;

class Router
{
	private Response $response;
	private Node $handler;
	private Directory $root;

	public function __construct(protected readonly Context $context)
	{

	}

	/**
	 * @param Application $application
	 * @return $this
	 * @throws Exception
	 */
	public function andFindHandler(Application $application):self
	{
		if(!$this->context->request->isModificationRequest())
			$application->configurePassiveNode($this->root);
		else
			$application->configureActiveNode($this->root)
		;
		$this->handler = new Navigator()
			->setRoot($this->root)
			->setPath($this->context->request->path)
			->navigate();
		;
		return $this;
	}

	public function toProcessExchange(Services $services):self
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