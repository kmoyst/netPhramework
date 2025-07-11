<?php

namespace netPhramework\core;

use netPhramework\common\Variables;
use netPhramework\exceptions\PathException;
use netPhramework\locating\redirectors\RedirectToRoot;
use netPhramework\locating\Location;
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
	public function __construct(public string $callbackKey) {}

	/**
	 * Generates a callback link (usually to be added to a form in passive node)
	 *
	 * @param Location $location
	 * @return string|Encodable
	 */
	public function getLink(Location $location):string|Encodable
	{
		$callback = $location->getParameters()->getOrNull($this->callbackKey);
		return $callback ?? $location;
	}

	/**
	 * Returns a dispatcher to a callback Location if it exists in the current
	 * Location's parameters (referenced by callbackKey). Null otherwise.
	 *
	 * @return RedirectToRoot|null - dispatcher to callback, null if absent
	 * @throws PathException
	 */
	public function getRedirector(Variables $parameters):?RedirectToRoot
	{
		$callbackUri = $parameters->getOrNull($this->callbackKey);
		if(!$callbackUri) return null;
		$target = new LocationFromUri($callbackUri);
		return new RedirectToRoot($target->getPath(), $target->getParameters());
	}
}