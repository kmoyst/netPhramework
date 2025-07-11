<?php

namespace netPhramework\presentation;


use netPhramework\common\Variables;
use netPhramework\exchange\CallbackContext;
use netPhramework\rendering\Viewable;

class CallbackInput extends Viewable
{
	/**
	 * @param CallbackContext $context
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
		private readonly CallbackContext $context,
		private readonly bool $chain = false) {}

	public function getTemplateName(): string
	{
		return 'form/hidden-input';
	}

	public function getVariables(): iterable
	{
		return new Variables()
			->add('name', $this->context->callbackKey)
			->add('value', $this->context->callbackLink($this->chain))
			;
	}
}