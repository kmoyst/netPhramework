<?php

namespace netPhramework\exchange;

use netPhramework\core\Node;
use netPhramework\responding\Response;

abstract class RequestProcess
{
	public function __construct (protected Node $node) {}

	abstract public function exchange(RequestContext $context):Response;
}