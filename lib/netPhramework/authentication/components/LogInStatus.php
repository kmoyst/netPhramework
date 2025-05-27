<?php

namespace netPhramework\authentication\components;

use netPhramework\core\Node;
use netPhramework\core\Exchange;
use netPhramework\core\LeafTrait;
use netPhramework\exceptions\InvalidSession;
use netPhramework\rendering\View;

class LogInStatus implements Node
{
	use LeafTrait;

	/**
	 * @param Exchange $exchange
	 * @return void
	 * @throws InvalidSession
	 */
	public function handleExchange(Exchange $exchange): void
	{
		if($exchange->getSession()->confirmLoggedIn())
		{
			$user = $exchange->getSession()->getUser();
			$message = "You are logged in. ";
			$message .= "You are a ".$user->getRole()->friendlyName().". ";
		}
		else
			$message = "You are not logged in. ";
		$message .= $exchange->getParameters()->getOrNull('message' ?? '');
		$view = new View('message');
		$view->getVariables()->add('message', rtrim($message));
		$exchange->ok($view->setTitle('Check Status'));
	}
}