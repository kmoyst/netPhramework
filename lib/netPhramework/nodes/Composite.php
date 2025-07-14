<?php

namespace netPhramework\nodes;

use netPhramework\exchange\Exchange;
use netPhramework\routing\redirectors\RedirectToChild;

abstract class Composite implements Node
{
	public function getResourceId():string
	{
		return $this->getName();
	}

	/**
	 * @param Exchange $exchange
	 * @return void
	 */
	public function handleExchange(Exchange $exchange): void
	{
		$exchange->redirect(new RedirectToChild('',$exchange->parameters));
	}
}