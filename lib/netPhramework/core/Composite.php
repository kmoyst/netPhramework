<?php

namespace netPhramework\core;

use netPhramework\dispatching\redirectors\RedirectToChild;
use netPhramework\exceptions\InvalidUri;

trait Composite
{
	/**
	 * @param Exchange $exchange
	 * @return void
	 * @throws InvalidUri
	 */
	public function handleExchange(Exchange $exchange): void
	{
		$exchange->redirect(new RedirectToChild('',$exchange->getParameters()));
	}
}