<?php

namespace netPhramework\authentication;

use netPhramework\core\Exchange;
use netPhramework\core\Leaf;
use netPhramework\rendering\View;

class LogInStatus extends Leaf
{
	public function handleExchange(Exchange $exchange): void
	{
		if($exchange->getSession()->confirmLoggedIn())
		{
			$user = $exchange->getSession()->getUser();
			$message = "You are logged in. ";
			$message .= "Your role is ".$user->getRole()->name.". ";
		}
		else
			$message = "You are not logged in. ";
		$message .= $exchange->getParameters()->getOrNull('message' ?? '');
		$view = new View('message');
		$view->getVariables()->add('message', rtrim($message));
		$exchange->ok($view->setTitle('Check Status'));
	}
}