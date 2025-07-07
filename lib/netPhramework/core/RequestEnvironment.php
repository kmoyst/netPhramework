<?php

namespace netPhramework\core;

interface RequestEnvironment
{
	public function getUri(): string;
	public function getPostParameters(): ?array;
}