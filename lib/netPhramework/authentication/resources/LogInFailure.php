<?php

namespace netPhramework\authentication\resources;

use netPhramework\core\Exchange;
use netPhramework\core\LeafResource;
use netPhramework\rendering\View;

class LogInFailure extends LeafResource
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