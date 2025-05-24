<?php

namespace netPhramework\core;

use netPhramework\common\Variables;
use netPhramework\dispatching\dispatchers\DispatchToRoot;
use netPhramework\dispatching\interfaces\ReadableLocation;
use netPhramework\dispatching\Location;
use netPhramework\dispatching\Path;
use netPhramework\dispatching\UriAdapter;

/**
 * A central manager for callbacks usually used by SocketExchange.
 */
readonly class CallbackManager
{
	/**
	 * Takes necessary context to function, usually from SocketExchange
	 *
	 * @param string $callbackKey
	 * @param Path $requestPath
	 * @param Variables $parameters
	 */
	public function __construct(
		private string $callbackKey,
		private Path   $requestPath,
		private Variables $parameters) {}

	/**
	 * Key used for callback inputs and location parameters.
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
	 * return the current ReadableLocation. True interjects with current location
	 * even when callback is present. It propagates the existing callback to
	 * allows that information to be preserved upon return to the current
	 * location.
	 *
	 * @return string|ReadableLocation
	 */
	public function callbackLink(bool $chain):string|ReadableLocation
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
	 * Returns a dispatcher to a callback ReadableLocation if it exists in the current
	 * ReadableLocation's parameters (referenced by callbackKey). Null otherwise.
	 *
	 * @return DispatchToRoot|null - dispatcher to callback, null if absent
	 * @throws Exception
	 */
	public function callbackDispatcher():?DispatchToRoot
	{
		if(!($callbackUri = $this->fromParameters())) return null;
		$adapter = new UriAdapter($callbackUri);
		return new DispatchToRoot(
			$adapter->getPath(), $adapter->getParameters());
	}

	/**
	 * Private method. Retrieves callback from parameters. Returns null if
	 * none exists.
	 *
	 * @return string|null
	 */
	private function fromParameters():?string
	{
		return $this->parameters->getOrNull($this->callbackKey);
	}

	/**
	 * Generates a readable location based on the contained Path / Parameters.
	 * Cloned and immutable.
	 *
	 * @return Location
	 */
	private function fromCurrentLocation():Location
	{
		return new Location(
			clone $this->requestPath, clone $this->parameters);
	}
}