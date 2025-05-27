<?php

namespace netPhramework\authentication\nodes;

use netPhramework\core\Node;
use netPhramework\core\Exchange;
use netPhramework\core\LeafTrait;
use netPhramework\rendering\View;

class LogInFailure implements Node
{
	use LeafTrait;

	public function handleExchange(Exchange $exchange): void
	{
		$message = $exchange->getParameters()->getOrNull('message' ?? '');
		$exchange->ok(new View('message'))
			->setTitle('Check Status')
			->add('message', $message)
		;
	}
}