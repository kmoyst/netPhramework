<?php

namespace netPhramework\dispatching;

use netPhramework\common\Variables;
use netPhramework\exceptions\Exception;

readonly class CallbackManager
{
	public function __construct(
		private string $callbackKey,
		private Path   $requestPath,
		private Variables $parameters) {}

	public function getCallbackKey(): string
	{
		return $this->callbackKey;
	}

	public function sticky():string|Location
	{
		return $this->retrieve() ?: $this->generate();
	}

	/**
	 * @return Callback|null
	 * @throws Exception
	 */
	public function resolve():?Callback
	{
		if(!($callbackUri = $this->retrieve())) return null;
		$adapter = new UriAdapter($callbackUri);
		return new Callback($adapter->getPath(), $adapter->getParameters());
	}

	private function retrieve():?string
	{
		return $this->parameters->getOrNull($this->callbackKey);
	}

	private function generate():Location
	{
		return new ReadableLocation(
			clone $this->requestPath, clone $this->parameters);
	}
}