<?php

namespace netPhramework\exchange;

use netPhramework\rendering\Encodable;

interface CallbackContext
{
	/**
	 * Callback Key
	 *
	 * @var string
	 */
	public string $callbackKey {get;}

	/**
	 * Generates a callback link (usually to be added to a form in passive node)
	 *
	 * If $chain is false (default) Returns any callback Location found
	 * in the Exchange Parameters OR the current Location if none is
	 * found.
	 *
	 * If $chain is true, it will insert the current Location as the
	 * first in
	 * the callback chain and append any callback Location found in
	 * the Exchange
	 * Parameters.
	 *
	 * @param bool $chain - false: defer to existing callback, true: inject
	 * current location into callback chain to request a return to this
	 * location
	 * first.
	 *
	 * @return string|Encodable
	 */
	public function callbackLink(bool $chain = false):string|Encodable;
}