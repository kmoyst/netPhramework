<?php

namespace netPhramework\core;

use netPhramework\common\Variables;
use netPhramework\exceptions\PathException;
use netPhramework\locating\redirectors\RedirectToRoot;
use netPhramework\locating\MutableLocation;
use netPhramework\locating\LocationFromUri;
use netPhramework\rendering\Encodable;

/**
 * A central manager for callbacks usually used by SocketExchange.
 */
readonly class CallbackManager
{
	/**
	 * Takes necessary context to function, usually from SocketExchange
	 *
	 * @param string $callbackKey
	 */
	public function __construct(private string $callbackKey) {}

	/**
	 * QueryKey used for callback inputs and location parameters.
	 * Set by SiteContext.
	 *
	 * @return string
	 */
	public function getCallbackKey(): string
	{
		return $this->callbackKey;
	}

	/**
	 * Generates a callback link (usually to be added to a form in passive node)
	 *
	 * @param MutableLocation $location
	 * @param bool $chain - False only uses current location when
	 * existing callback is not present. If no callback is present, it WILL
	 * return the current Location. True interjects with current location
	 * even when callback is present. It propagates the existing callback to
	 * allows that information to be preserved upon return to the current
	 * location.
	 *
	 * @return string|Encodable
	 */
	public function callbackLink(
		MutableLocation $location, bool $chain):string|Encodable
	{
		$callback = $location->getParameters()->getOrNull($this->callbackKey);
		return !$chain && $callback ? $callback : $location;
	}

	/**
	 * Returns a dispatcher to a callback Location if it exists in the current
	 * Location's parameters (referenced by callbackKey). Null otherwise.
	 *
	 * @return RedirectToRoot|null - dispatcher to callback, null if absent
	 * @throws PathException
	 */
	public function callbackRedirector(Variables $parameters):?RedirectToRoot
	{
		$callbackUri = $parameters->getOrNull($this->callbackKey);
		if(!$callbackUri) return null;
		$target = new LocationFromUri($callbackUri);
		return new RedirectToRoot($target->getPath(), $target->getParameters());
	}
}