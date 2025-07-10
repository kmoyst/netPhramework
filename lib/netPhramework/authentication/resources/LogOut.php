<?php

namespace netPhramework\authentication\resources;

use netPhramework\core\LeafResource;
use netPhramework\core\Exception;
use netPhramework\core\Exchange;
use netPhramework\locating\redirectors\Redirector;
use netPhramework\locating\redirectors\RedirectToRoot;
use netPhramework\exceptions\InvalidSession;

class LogOut extends LeafResource
{
	public function __construct(
		private readonly ?Redirector $dispatcher = null) {}

	/**
	 * @param Exchange $exchange
	 * @return void
	 * @throws Exception
	 * @throws InvalidSession
	 */
	public function handleExchange(Exchange $exchange): void
	{
		$exchange->getSession()->logout();
		$exchange->redirect($this->dispatcher ?? new RedirectToRoot());
	}
}