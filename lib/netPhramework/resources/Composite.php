<?php

namespace netPhramework\resources;

use netPhramework\exchange\Exchange;
use netPhramework\routing\redirectors\RedirectToChild;

abstract class Composite implements Resource
{
	public function getResourceId():string
	{
		return $this->getName();
	}

	public function handleExchange(Exchange $exchange): void
	{
		$exchange->redirect(new RedirectToChild('',$exchange->parameters));
	}
}