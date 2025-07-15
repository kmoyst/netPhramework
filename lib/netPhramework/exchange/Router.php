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
	private(set) Exchange $exchange;

	private Directory $root;


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
		return $this;
	}

	/**
	 * @param Location $location
	 * @return self
	 * @throws NodeNotFound
	 */
	public function andFindHandler(Location $location):self
	{
		$this->handler = new Navigator()
			->setRoot($this->root)
			->setPath($location->path)
			->navigate()
		;
		$this->exchange = new Exchange($location);
		return $this;
	}

	public function toProcessExchange(
		Environment $environment, Services $services):self
	{
		$this->exchange
			->setEnvironment($environment)
			->setCallbackManager($services->callbackManager)
			->setFileManager($services->fileManager)
			->setSession($services->session)
			->setSmtpServer($services->smtpServer)
		;
		$this->handler->handleExchange($this->exchange);
		$this->response = $this->exchange->response;
		return $this;
	}

	public function andDeliverResponseThrough(Responder $responder):void
	{
		$this->response->deliver($responder);
	}
}