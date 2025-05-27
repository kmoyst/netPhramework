<?php

namespace netPhramework\core;

use netPhramework\dispatching\redirectors\RedirectToChild;

abstract class Composite implements Component
{
	public function handleExchange(Exchange $exchange): void
	{
		$exchange->redirect(new RedirectToChild('',$exchange->getParameters()));
	}
}