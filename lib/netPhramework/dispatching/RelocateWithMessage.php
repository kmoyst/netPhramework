<?php

namespace netPhramework\dispatching;

abstract class RelocateWithMessage implements Relocator
{
	protected string $message;
	protected string $messageKey = 'message';

	public function setMessage(string $message):self
	{
		$this->message = $message;
		return $this;
	}

	public function setMessageKey(string $messageKey):self
	{
		$this->messageKey = $messageKey;
		return $this;
	}
}