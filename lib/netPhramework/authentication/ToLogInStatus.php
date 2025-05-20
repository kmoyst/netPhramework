<?php

namespace netPhramework\authentication;

use netPhramework\dispatching\Relocatable;
use netPhramework\dispatching\RelocateToSibling;
use netPhramework\dispatching\RelocateWithMessage;

class ToLogInStatus extends RelocateWithMessage
{
	public function __construct(
		private readonly string $siblingName = 'log-in-status',
		?string $messageKey = null)
	{
		if($messageKey !== null) $this->messageKey = $messageKey;
	}

	public function relocate(Relocatable $location): void
	{
		$delegate = new RelocateToSibling($this->siblingName);
		$delegate->relocate($location);
		$location->getParameters()->add($this->messageKey,$this->message ?? '');
	}
}