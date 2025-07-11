<?php

namespace netPhramework\exchange;

use netPhramework\site\Node;

abstract class RequestProcess
{
	public function __construct (protected Node $node) {}

	abstract public function exchange(RequestContext $context):Response;
}