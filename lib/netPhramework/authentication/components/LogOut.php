<?php

namespace netPhramework\authentication\components;

use netPhramework\core\Exchange;
use netPhramework\core\Leaf;
use netPhramework\dispatching\Dispatcher;
use netPhramework\dispatching\DispatchToRootLeaf;

class LogOut extends Leaf
{
	public function __construct(
		private readonly ?Dispatcher $dispatcher = null,
		?string $name = null) { parent::__construct($name); }

	public function handleExchange(Exchange $exchange): void
	{
		$exchange->getSession()->logout();
		$exchange->redirect($this->dispatcher ?? new DispatchToRootLeaf());
	}
}