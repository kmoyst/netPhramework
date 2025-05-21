<?php

namespace netPhramework\dispatching;

class RelocateToSiblingWithMessage extends RelocateWithMessage
{
	public function __construct(
		private readonly string $siblingLeaf,
		private readonly string $messageKey = 'message') {}

	public function relocate(Relocatable $location): void
	{
		new DispatchToSibling($this->siblingLeaf)->relocate($location);
		$location->getParameters()->add($this->messageKey, $this->message);
	}
}