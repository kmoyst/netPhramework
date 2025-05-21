<?php

namespace netPhramework\authentication;

use netPhramework\dispatching\DispatchToSibling;
use netPhramework\dispatching\Relocatable;
use netPhramework\dispatching\RelocateWithMessage;

class ToLoginStatus extends RelocateWithMessage
{
	public function __construct(
		private readonly string $siblingLeaf = 'log-in-status',
		private readonly string $messageKey = 'message',
		string $message = '') { $this->message = $message; }

	public function relocate(Relocatable $location): void
	{
		new DispatchToSibling($this->siblingLeaf)->relocate($location);
		$location->getParameters()->add($this->messageKey, $this->message);
	}
}