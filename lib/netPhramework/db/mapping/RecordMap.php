<?php

namespace netPhramework\db\mapping;

readonly class RecordMap
{
	public function __construct
	(
	public string $assetName,
	public string $mappedName
	)
	{}
}