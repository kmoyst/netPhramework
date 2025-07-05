<?php

namespace netPhramework\presentation;


use netPhramework\common\Variables;
use netPhramework\core\Exchange;
use netPhramework\rendering\Viewable;

class CallbackInput extends Viewable
{
	/**
	 * @param Exchange $exchange
	 * @param bool $chain - False (default) only uses current Location
	 *   when
	 *   existing callback is not present. If no callback is present, it WILL
	 *   return the current Location. True interjects with current
	 *   location
	 *   even when callback is present. It propagates the existing callback to
	 *   allow that information to be preserved upon return to the current
	 *   location.
	 */
	public function __construct(
		private readonly Exchange $exchange,
		private readonly bool $chain = false) {}

	public function getTemplateName(): string
	{
		return 'form/hidden-input';
	}

	public function getVariables(): iterable
	{
		return new Variables()
			->add('name', $this->exchange->getCallbackKey())
			->add('value', $this->exchange->callbackLink($this->chain))
			;
	}
}