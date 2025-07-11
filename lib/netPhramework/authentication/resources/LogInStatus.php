<?php

namespace netPhramework\authentication\resources;

use netPhramework\exceptions\InvalidSession;
use netPhramework\exchange\Exchange;
use netPhramework\rendering\View;
use netPhramework\resources\Leaf;

class LogInStatus extends Leaf
{
	/**
	 * @param Exchange $exchange
	 * @return void
	 * @throws InvalidSession
	 */
	public function handleExchange(Exchange $exchange): void
	{
		if($exchange->session->confirmLoggedIn())
		{
			$user = $exchange->session->user;
			$message = "You are logged in. ";
			$message .= "You are a ".$user->getRole()->friendlyName().". ";
		}
		else
			$message = "You are not logged in. ";
		$message .= $exchange->parameters->getOrNull('message' ?? '');
		$view = new View('message');
		$view->getVariables()->add('message', rtrim($message));
		$exchange->ok($view->setTitle('Check Status'));
	}
}