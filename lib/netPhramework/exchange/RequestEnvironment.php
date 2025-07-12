<?php

namespace netPhramework\exchange;

interface RequestEnvironment extends ExchangeEnvironment
{
	public string $uri {get;}
	public ?array $postParameters{get;}
}