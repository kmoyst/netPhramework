<?php

namespace netPhramework\core;

use netPhramework\dispatching\redirectors\RedirectToChild;

abstract class Composite implements Component
{
	/**
	 * @param Exchange $exchange
	 * @return void
	 * @throws Exception
	 */
	public function handleExchange(Exchange $exchange): void
	{
		$exchange->redirect(new RedirectToChild('',$exchange->getParameters()));
	}
}