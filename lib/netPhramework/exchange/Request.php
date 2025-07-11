<?php

namespace netPhramework\exchange;

use netPhramework\core\Node;
use netPhramework\locating\Location;
use netPhramework\responding\Response;

readonly class Request
{
	public function __construct(private Node $node, public Location $location)
	{}

	/**
	 * @param ExchangeContext $context
	 * @return Response
	 */
	public function process(ExchangeContext $context):Response
	{
		$exchange = new RequestExchange($this->location, $context);
		return $this->node->processRequest($exchange);
	}
}