<?php

namespace netPhramework\data\core;
use Stringable;

readonly class RecordMap implements Stringable
{
	public function __construct
	(
	public string $resourceName,
	public string $recordSetName
	)
	{}

	public function __toString(): string
	{
		return $this->recordSetName;
	}
}