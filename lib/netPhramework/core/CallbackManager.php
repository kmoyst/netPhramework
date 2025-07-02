<?php

namespace netPhramework\core;

use netPhramework\exceptions\PathException;
use netPhramework\locating\redirectors\RedirectToRoot;
use netPhramework\locating\MutableLocation;
use netPhramework\locating\Location;
use netPhramework\locating\LocationFromUri;
use netPhramework\exceptions\InvalidUri;

/**
 * A central manager for callbacks usually used by SocketExchange.
 */
readonly class CallbackManager
{
	/**
	 * Takes necessary context to function, usually from SocketExchange
	 *
	 * @param string $callbackKey
	 * @param MutableLocation $location
	 */
	public function __construct(
		private string $callbackKey,
		private MutableLocation $location) {}

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
	 * @param bool $chain - False only uses current location when
	 * existing callback is not present. If no callback is present, it WILL
	 * return the current Location. True interjects with current location
	 * even when callback is present. It propagates the existing callback to
	 * allows that information to be preserved upon return to the current
	 * location.
	 *
	 * @return string|Location
	 */
	public function callbackLink(bool $chain):string|MutableLocation
	{
		if($chain)
		{
			$location = $this->fromCurrentLocation();
			if($caller = $this->fromParameters())
				$location->getParameters()->add($this->callbackKey, $caller);
			return $location;
		}
		else
		{
			return
				$this->fromParameters() ??
				$this->fromCurrentLocation();
		}
	}

	/**
	 * Returns a dispatcher to a callback Location if it exists in the current
	 * Location's parameters (referenced by callbackKey). Null otherwise.
	 *
	 * @return RedirectToRoot|null - dispatcher to callback, null if absent
	 * @throws InvalidUri
	 * @throws PathException
	 */
	public function callbackRedirector():?RedirectToRoot
	{
		if(!($callbackUri = $this->fromParameters())) return null;
		$location = new LocationFromUri($callbackUri);
		return new RedirectToRoot(
			$location->getPath(), $location->getParameters());
	}

	/**
	 * Private method. Retrieves callback from parameters. Returns null if
	 * none exists.
	 *
	 * @return string|null
	 */
	private function fromParameters():?string
	{
		return $this->location->getParameters()->getOrNull($this->callbackKey);
	}

	/**
	 * Generates a readable location based on the contained
	 * MutablePath / Parameters.
	 *
	 * @return MutableLocation
	 */
	private function fromCurrentLocation():MutableLocation
	{
		return clone $this->location;
	}
}