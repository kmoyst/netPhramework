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

	/**
	 * Check if node is a composite
	 *
	 * @return bool
	 */
	public function isComposite():bool
	{
		return true;
	}

	/**
	 * Test if node is a Leaf
	 *
	 * @return bool
	 */
	public function isLeaf():bool
	{
		return false;
	}
}