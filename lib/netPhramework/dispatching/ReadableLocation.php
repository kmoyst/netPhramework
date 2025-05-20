<?php

namespace netPhramework\dispatching;

readonly class ReadableLocation implements Location
{
	public function __construct(
		private ReadablePath $path,
		private iterable $parameters) {}

    public function getPath(): ReadablePath
    {
        return $this->path;
    }

    public function getParameters(): iterable
    {
        return $this->parameters;
    }
}