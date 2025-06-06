<?php

namespace netPhramework\core;

use netPhramework\locating\redirectors\RedirectToChild;
use netPhramework\exceptions\InvalidUri;

trait CompositeTrait
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