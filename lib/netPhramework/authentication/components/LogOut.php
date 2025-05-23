<?php

namespace netPhramework\authentication\components;

use netPhramework\core\Exception;
use netPhramework\core\Exchange;
use netPhramework\core\Leaf;
use netPhramework\dispatching\dispatchers\Dispatcher;
use netPhramework\dispatching\dispatchers\DispatchToRoot;
use netPhramework\exceptions\InvalidSession;

class LogOut extends Leaf
{
	public function __construct(
		private readonly ?Dispatcher $dispatcher = null,
		?string $name = null) { parent::__construct($name); }

	/**
	 * @param Exchange $exchange
	 * @return void
	 * @throws Exception
	 * @throws InvalidSession
	 */
	public function handleExchange(Exchange $exchange): void
	{
		$exchange->getSession()->logout();
		$exchange->redirect($this->dispatcher ?? new DispatchToRoot());
	}
}