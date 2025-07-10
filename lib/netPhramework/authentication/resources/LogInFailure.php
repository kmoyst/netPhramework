<?php

namespace netPhramework\authentication\resources;

use netPhramework\core\Exchange;
use netPhramework\core\Leaf;
use netPhramework\rendering\View;

class LogInFailure extends Leaf
{
	public function handleExchange(Exchange $exchange): void
	{
		$message = $exchange->getParameters()->getOrNull('message' ?? '');
		$exchange->ok(new View('message'))
			->setTitle('Check Status')
			->add('message', $message)
		;
	}
}