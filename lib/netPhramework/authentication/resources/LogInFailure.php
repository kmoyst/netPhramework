<?php

namespace netPhramework\authentication\resources;

use netPhramework\exchange\Exchange;
use netPhramework\rendering\View;
use netPhramework\resources\Leaf;

class LogInFailure extends Leaf
{
	public function handleExchange(Exchange $exchange): void
	{
		$message = $exchange->parameters->getOrNull('message' ?? '');
		$exchange->ok(new View('message'))
			->setTitle('Check Status')
			->add('message', $message)
		;
	}
}