<?php

namespace netPhramework\core;

abstract class Composite implements Component
{
	public function handleExchange(Exchange $exchange): void
	{
		$exchange->getPath()->append('');
		$exchange->seeOther();
	}
}