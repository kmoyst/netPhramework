<?php

namespace netPhramework\core;

use netPhramework\locating\redirectors\RedirectToChild;

trait CompositeTrait
{
	public function getComponentName():string
	{
		return $this->getName();
	}

	public function handleExchange(Exchange $exchange): void
	{
		$exchange->redirect(new RedirectToChild('',$exchange->getParameters()));
	}
}