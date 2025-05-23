<?php

namespace netPhramework\rendering;

use netPhramework\dispatching\interfaces\ReadableLocation;
use netPhramework\dispatching\interfaces\ReadablePath;

interface ViewConfiguration
{
	public function add(string $key,
						string|Viewable|Encodable|ReadablePath|ReadableLocation|
						iterable|null $value):ViewConfiguration;

	public function setTitle(?string $title): ViewConfiguration;
}