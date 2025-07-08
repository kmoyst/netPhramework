<?php

namespace netPhramework\core;

use netPhramework\locating\redirectors\RedirectToChild;

abstract class Composite implements Node
{
	public function getNodeId():string
	{
		return $this->getName();
	}

	public function handleExchange(Exchange $exchange): void
	{
		$exchange->redirect(new RedirectToChild('',$exchange->getParameters()));
	}
}