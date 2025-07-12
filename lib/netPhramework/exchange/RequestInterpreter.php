<?php

namespace netPhramework\exchange;

use netPhramework\routing\Location;
use netPhramework\routing\LocationFromUri;

class RequestInterpreter
{
	protected Location $location;
	protected RequestExchange $exchange;

	public function initialize(RequestContext $context):self
	{
		$this->location = new LocationFromUri($context->environment->uri);
		$this->exchange = new RequestExchange($this->location, $context);
		return $this;
	}

	/**
	 * @param RequestContext $context
	 * @return RequestProcess
	 */
	public function interpret(RequestContext $context):RequestProcess
	{
		if($context->environment->postParameters === null)
			return new PassiveProcess($this->location, $this->exchange);
		else
			return new ActiveProcess($this->location, $this->exchange);
	}
}