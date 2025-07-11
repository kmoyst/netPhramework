<?php

namespace netPhramework\exchange;

use netPhramework\core\Node;
use netPhramework\responding\Response;

readonly class Request
{
	public function __construct
	(
		private Node $node,
		private RequestExchange $exchange
	)
	{}

	public function process():Response
	{
		return $this->node->exchange($this->exchange);
	}
}