<?php

namespace netPhramework\core;

use netPhramework\locating\redirectors\RedirectToChild;

abstract class CompositeResource implements Resource
{
	public function getResourceId():string
	{
		return $this->getName();
	}

	public function handleExchange(Exchange $exchange): void
	{
		$exchange->redirect(new RedirectToChild('',$exchange->getParameters()));
	}
}