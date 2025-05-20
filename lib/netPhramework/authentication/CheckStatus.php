<?php

namespace netPhramework\authentication;

use netPhramework\core\Exchange;
use netPhramework\core\Leaf;
use netPhramework\rendering\View;

class CheckStatus extends Leaf
{
	public function handleExchange(Exchange $exchange): void
	{
		if($exchange->getSession()->confirmLoggedIn())
			$message = "You are logged in";
		else
			$message = "You are not logged in";
		$view = new View('message');
		$view->getVariables()->add('message', $message);
		$exchange->ok($view->setTitle('Check Status'));
	}
}