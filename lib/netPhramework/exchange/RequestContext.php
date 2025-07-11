<?php

namespace netPhramework\exchange;

interface RequestContext extends ExchangeContext
{
	public RequestEnvironment $environment { get; }
}