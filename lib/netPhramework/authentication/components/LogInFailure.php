<?php

namespace netPhramework\authentication\components;

use netPhramework\core\Node;
use netPhramework\core\Exchange;
use netPhramework\core\Leaf;
use netPhramework\rendering\View;

class LogInFailure implements Node
{
	use Leaf;

	public function handleExchange(Exchange $exchange): void
	{
		$message = $exchange->getParameters()->getOrNull('message' ?? '');
		$exchange->ok(new View('message'))
			->setTitle('Check Status')
			->add('message', $message)
		;
	}
}