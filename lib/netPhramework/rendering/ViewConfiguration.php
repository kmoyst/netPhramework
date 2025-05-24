<?php

namespace netPhramework\rendering;

use netPhramework\dispatching\interfaces\ReadablePath;
use netPhramework\dispatching\ReadableLocation;

interface ViewConfiguration
{
	public function add(string $key, string|Encodable|
									 iterable|null $value):ViewConfiguration;

	public function setTitle(?string $title): ViewConfiguration;
}