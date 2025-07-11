<?php

namespace netPhramework\exchange;

use netPhramework\core\Node;

abstract class RequestProcess
{
	public function __construct (protected Node $node) {}

	abstract public function request(RequestContext $context):Request;
}