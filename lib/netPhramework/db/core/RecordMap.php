<?php

namespace netPhramework\db\core;

readonly class RecordMap
{
	public function __construct
	(
	public string $assetName,
	public string $mappedName
	)
	{}
}