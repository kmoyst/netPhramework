<?php

namespace netPhramework\dispatching;

class DispatchToSiblingWithMessage extends DispatchWithMessage
{
	public function __construct(
		private readonly string $siblingLeaf,
		private readonly string $messageKey = 'message') {}

	public function dispatch(Dispatchable $dispatchable): void
	{
		$relocator = new RelocateToSibling($this->siblingLeaf);
		$relocator->relocate($dispatchable);
		$dispatchable->getParameters()->add($this->messageKey, $this->message);
		$dispatchable->seeOther();
	}
}