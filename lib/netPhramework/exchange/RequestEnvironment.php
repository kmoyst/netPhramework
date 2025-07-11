<?php

namespace netPhramework\exchange;

interface RequestEnvironment
{
	public string $uri {get;}
	public ?array $postParameters{get;}
}