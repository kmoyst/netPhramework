<?php

namespace netPhramework\authentication\nodes;

use netPhramework\core\Node;
use netPhramework\core\Exception;
use netPhramework\core\Exchange;
use netPhramework\core\LeafTrait;
use netPhramework\locating\redirectors\Redirector;
use netPhramework\locating\redirectors\RedirectToRoot;
use netPhramework\exceptions\InvalidSession;

class LogOut extends Node
{
	use LeafTrait;

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