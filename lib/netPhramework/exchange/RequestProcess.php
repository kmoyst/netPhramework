<?php

namespace netPhramework\exchange;


use netPhramework\core\Node;
use netPhramework\exceptions\Exception;
use netPhramework\exceptions\ResourceNotFound;
use netPhramework\routing\Location;

abstract class RequestProcess
{
	protected Node $node;

	public function __construct
	(
	protected Location $location,
	protected RequestExchange $exchange,
	)
	{
		$this->node = new Node();
	}

	/**
	 * @return Response
	 * @throws ResourceNotFound
	 */
	public function exchange():Response
	{
		$this->node->handleExchange($this->exchange);
		return $this->exchange->response;
	}

	/**
	 * @param RequestContext $context
	 * @throws Exception
	 * @return self
	 */
	abstract public function prepare(RequestContext $context):self;
}