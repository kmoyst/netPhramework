<?php

namespace netPhramework\dispatching;

/**
 * The purpose of this class is simply to provide the setMessage() method
 * and property on top a relocator.
 */
abstract class DispatchWithMessage implements Dispatcher
{
	protected string $message = '';

	public function setMessage(string $message):self
	{
		$this->message = $message;
		return $this;
	}
}