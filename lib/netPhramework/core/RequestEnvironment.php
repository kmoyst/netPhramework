<?php

namespace netPhramework\core;

interface RequestEnvironment
{
	public string $uri {get;}
	public ?array $postParameters{get;}
}