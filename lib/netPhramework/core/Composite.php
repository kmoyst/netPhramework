<?php

namespace netPhramework\core;

use netPhramework\dispatching\dispatchers\DispatchToChild;
use netPhramework\exceptions\Exception;

abstract class Composite implements Component
{
	/**
	 * @param Exchange $exchange
	 * @return void
	 * @throws Exception
	 */
	public function handleExchange(Exchange $exchange): void
	{
		$exchange->redirect(new DispatchToChild(''));
	}
}