<?php

namespace netPhramework\nodes;

use netPhramework\exceptions\PathException;
use netPhramework\exchange\Exchange;
use netPhramework\routing\redirectors\RedirectToChild;

abstract class Composite implements Node
{
	public string $id {get{return $this->getResourceId();}}

	public function getResourceId():string
	{
		return $this->getName();
	}

	/**
	 * @param Exchange $exchange
	 * @return void
	 * @throws PathException
	 */
	public function handleExchange(Exchange $exchange): void
	{
		$exchange->redirect(new RedirectToChild('',$exchange->parameters));
	}
}