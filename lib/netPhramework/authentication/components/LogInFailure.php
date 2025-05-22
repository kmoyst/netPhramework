<?php

namespace netPhramework\authentication\components;

use netPhramework\core\Exchange;
use netPhramework\core\Leaf;
use netPhramework\rendering\View;

class LogInFailure extends Leaf
{
	public function handleExchange(Exchange $exchange): void
	{
		$message = $exchange->getParameters()->getOrNull('message' ?? '');
		$view = new View('message');
		$view->getVariables()->add('message', $message);
		$exchange->ok($view->setTitle('Check Status'));
	}
}