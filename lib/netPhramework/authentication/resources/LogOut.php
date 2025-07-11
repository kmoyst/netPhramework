<?php

namespace netPhramework\authentication\resources;

use netPhramework\exceptions\Exception;
use netPhramework\exceptions\InvalidSession;
use netPhramework\exchange\Exchange;
use netPhramework\routing\redirectors\Redirector;
use netPhramework\routing\redirectors\RedirectToRoot;
use netPhramework\resources\Leaf;

class LogOut extends Leaf
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
		$exchange->session->logout();
		$exchange->redirect($this->dispatcher ?? new RedirectToRoot());
	}
}