<?php

namespace netPhramework\exchange;

use netPhramework\exceptions\Exception;
use netPhramework\exceptions\ResourceNotFound;

class RequestProcessor
{
	private RequestSocket $socket;
	private Request $request;

	public function __construct(private readonly RequestContext $context) {}

	public function openSocket():self
	{
		if($this->context->environment->postParameters === null)
			$this->socket = new PassiveSocket();
		else
			$this->socket =  new ActiveSocket();
		return $this;
	}

	public function prepare():self
	{
		$this->request = new Request($this->context);
		return $this;
	}

	/**
	 * @return Response
	 * @throws Exception
	 * @throws ResourceNotFound
	 */
	public function process():Response
	{
		$this->socket
			->createNode($this->request, $this->context->getApplication())
			->handleExchange($this->request->exchange);
		return $this->request->exchange->response;
	}
}